<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\FixedExpense;
use App\Models\VariableExpense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $bankAccountBalance = BankAccount::query()->balance()->firstOrFail()->balance;

            if ($request->fixed == 'Sim') {
                $expense = FixedExpense::query()->findOrFail($request->id);
            } else {
                $expense = VariableExpense::query()->findOrFail($request->id);
            }

            if ($bankAccountBalance < $expense->amount) {
                return redirect()->back()->with('error', 'Saldo insuficiente para realizar o pagamento!');
            }

            $dueDate = $expense->due_date;

            if ($expense->day_of_month) {
                $dueDate = Carbon::create(now()->year, now()->month, $expense->day_of_month);
            }

            if ($request->fixed == 'Sim') {
                $expense->payments()->create([
                    'bank_account_id' => BankAccount::query()->latest()->first()->id,
                    'due_date' => $dueDate,
                    'payment_date' => now(),
                    'amount' => $expense->amount,
                    'status' => 'Pago'
                ]);
            } else {
                $expense->payment()->create([
                    'bank_account_id' => BankAccount::query()->latest()->first()->id,
                    'due_date' => $dueDate,
                    'payment_date' => now(),
                    'amount' => $expense->amount,
                    'status' => 'Pago'
                ]);

                $expense->update([
                    'status' => 'Pago'
                ]);
            }

            return response()->json([
                'message' => 'Pagamento realizado com sucesso!'
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
