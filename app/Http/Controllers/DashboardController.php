<?php

namespace App\Http\Controllers;

use App\Queries\MostConversionByUserQuery;
use App\Queries\ThirstHighestTransactionAmountQuery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function show(MostConversionByUserQuery $userQuery, ThirstHighestTransactionAmountQuery $transactionQuery)
    {
        $wallet = auth()->user()->wallet()->with('currency')->first();

        $totalConvertedAmount = $wallet?->moneySent()->sum('converted_amount');
        $thirdHighestTransactionAmount = $transactionQuery->get();
        $mostConversionByUser = $userQuery->get();

        return Inertia::render('Dashboard',
            compact('totalConvertedAmount', 'thirdHighestTransactionAmount', 'mostConversionByUser')
        );
    }
}
