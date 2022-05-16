<?php

namespace App\Http\Controllers;

use App\Events\MoneyTransferred;
use App\Models\MoneyTransfer;
use App\Models\Transaction;
use App\Models\User;
use App\Queries\MoneyTransferQuery;
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

    public function store(Request $request, CurrencyConverter $converter, MoneyTransferQuery $moneyTransferQuery)
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
            $convertedAmountArr = $converter->convert($request->input('amount'), $senderWallet->currency->code, $receiver->wallet->currency->code);
        }

        $moneyTransfer = $moneyTransferQuery->transfer(
            $request->input('amount'),
            $senderWallet,
            $receiver->wallet,
            $request->input('note'),
            $convertedAmountArr ?? null
        );

        event(new MoneyTransferred($moneyTransfer, $sender, $receiver));

        $request->session()->flash('flash.banner', 'Money Sent.');
        $request->session()->flash('flash.bannerStyle', 'success');

        return redirect('dashboard');
    }
}
