<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseItemRequest;
use App\Models\PurchaseItem;
use App\Models\PurchaseMonth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PurchaseItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PurchaseMonth $purchaseMonth)
    {
        return $purchaseMonth->purchaseItems()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseItemRequest $request, PurchaseMonth $purchaseMonth)
    {
        $data = $request->validated();

        try {
            return $purchaseMonth->purchaseItems()->create($data);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseMonth $purchaseMonth, PurchaseItem $purchaseItem)
    {
        return $purchaseItem;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PurchaseItemRequest $request, PurchaseMonth $purchaseMonth, PurchaseItem $purchaseItem)
    {
        $data = $request->validated();

        try {
            $purchaseItem->update($data);
            return $purchaseItem;
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseMonth $purchaseMonth, PurchaseItem $purchaseItem)
    {
        try {
            $purchaseItem->delete();
            return response('', Response::HTTP_NO_CONTENT);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
