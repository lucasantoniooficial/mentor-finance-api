<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\FixedExpense;
use App\Models\PurchaseMonth;
use App\Models\VariableExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $balanceAccount = BankAccount::query()->balance()->first()->balance;

        $purchaseMonths = PurchaseMonth::query()
            ->with('purchaseList')
            ->whereMonth('purchase_date', now()->month)
            ->get();

        $expenses = FixedExpense::query()->select([
            'id',
            DB::raw('"Sim" as fixed'),
            'description',
            DB::raw('CONCAT(day_of_month, "/", MONTH(now()), "/", YEAR(now())) as due_date'),
            'amount',
        ])->doesntHave('payments')->union(VariableExpense::query()
            ->select([
                'id',
                DB::raw('"Não" as fixed'),
                'description',
                DB::raw("DATE_FORMAT(due_date, '%d/%m/%Y') as due_date"),
                'amount',
            ])->doesntHave('payment')->whereMonth('due_date', now()->month))->get();

        return response()->json([
            'balanceAccount' => $balanceAccount,
            'purchaseMonths' => $purchaseMonths,
            'expenses' => $expenses
        ], Response::HTTP_OK);
    }
}
