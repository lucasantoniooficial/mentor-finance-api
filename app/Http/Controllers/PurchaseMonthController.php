<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseMonthRequest;
use App\Models\BankAccount;
use App\Models\PurchaseMonth;
use App\Models\VariableExpense;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PurchaseMonthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PurchaseMonth::query()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseMonthRequest $request)
    {
        $data = $request->validated();

        try {
            return PurchaseMonth::query()->create($data);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseMonth $purchaseMonth)
    {
        return $purchaseMonth;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PurchaseMonthRequest $request, PurchaseMonth $purchaseMonth)
    {
        $data = $request->validated();

        try {
            $purchaseMonth->update($data);

            return $purchaseMonth;
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseMonth $purchaseMonth)
    {
        try {
            $purchaseMonth->delete();

            return response('', Response::HTTP_NO_CONTENT);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changeStatusShipping(PurchaseMonth $purchaseMonth)
    {
        try {
            if ($purchaseMonth->is_pending) {
                $purchaseMonth->purchaseList->update(['status' => 'Comprando']);
                $purchaseMonth->update(['status' => 'Comprando']);
                return $purchaseMonth;
            }

            return response()->json([
                'message' => 'Esta compra ela não está pendente!'
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changeStatusFinished(Request $request, PurchaseMonth $purchaseMonth)
    {
        $data = $request->validate([
            'total' => 'required|numeric'
        ]);

        try {
            if ($purchaseMonth->is_shipping) {
                $data['status'] = 'Finalizada';
                $purchaseMonth->purchaseList->update(['status' => 'Finalizada']);
                $purchaseMonth->update($data);

                $purchaseMonth->payment()->create([
                    'bank_account_id' => BankAccount::query()->latest()->first()->id,
                    'amount' => $data['total'],
                    'due_date' => now(),
                    'payment_date' => now(),
                    'status' => 'Pago',
                ]);

                VariableExpense::query()->create([
                    'description' => $purchaseMonth->purchaseList->name,
                    'amount' => $data['total'],
                    'due_date' => now(),
                    'status' => 'Pago',
                ]);

                return $purchaseMonth;
            }

            return response()->json([
                'message' => 'Esta compra ela não está em andamento!'
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
