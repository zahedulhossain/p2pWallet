<?php

namespace App\Http\Controllers;

use App\Events\MoneyTransferred;
use App\Models\MoneyTransfer;
use App\Models\Transaction;
use App\Models\User;
use App\Services\CurrencyConverter\CurrencyConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MoneyTransferController extends Controller
{
    public function show()
    {
        $wallet = auth()->user()->wallet()->with('currency')->first();
        $balance = number_format($wallet->balance, 2);

        return Inertia::render('MoneyTransfer/Index', [
            'balance' => $balance,
            'currencySymbol' => $wallet->currency->symbol
        ]);
    }

    public function store(Request $request, CurrencyConverter $converter)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'note' => ['nullable', 'string', 'max:100']
        ]);

        if ($request->input('user_id') === auth()->id()) {
            $request->session()->flash('flash.banner', 'Oops! You selected yourself as the receiver.');
            $request->session()->flash('flash.bannerStyle', 'danger');

            return back();
        }

        $sender = auth()->user();
        $senderWallet = $sender->wallet()->with('currency')->first();

        if ($senderWallet->balance < $request->input('amount')) {
            $request->session()->flash('flash.banner', 'Your account balance is insufficient.');
            $request->session()->flash('flash.bannerStyle', 'danger');

            return back();
        }

        $receiver = User::query()->with('wallet.currency')->find($request->user_id);
        if ($receiver->wallet->currency->code !== $senderWallet->currency->code) {
            $convertedData = $converter->convert($request->input('amount'), $senderWallet->currency->code, $receiver->wallet->currency->code);
        }

        DB::beginTransaction();
        try {
            $moneyTransfer = MoneyTransfer::query()->create([
                'conversion_rate' => $convertedData['conversion_rate'] ?? null,
                'amount' => $request->input('amount'),
                'converted_amount' => $convertedData['converted_amount'] ?? null,
                'from_wallet_id' => $senderWallet->id,
                'to_wallet_id' => $receiver->wallet->id,
                'note' => $request->input('note')
            ]);

            Transaction::query()->create([
                'wallet_id' => $senderWallet->id,
                'amount' => $request->input('amount'),
                'action' => 'withdraw',
            ]);

            Transaction::query()->create([
                'wallet_id' => $receiver->wallet->id,
                'amount' => $convertedData['converted_amount'] ?? $request->input('amount'),
                'action' => 'deposit',
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        event(new MoneyTransferred($moneyTransfer, $sender, $receiver));

        $request->session()->flash('flash.banner', 'Money Sent.');
        $request->session()->flash('flash.bannerStyle', 'success');

        return redirect('dashboard');
    }
}
