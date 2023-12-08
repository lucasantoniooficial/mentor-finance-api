<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseListRequest;
use App\Models\PurchaseList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PurchaseListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PurchaseList::query()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseListRequest $request)
    {
        $data = $request->validated();

        try {
            return PurchaseList::query()->create($data);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseList $purchaseList)
    {
        return $purchaseList;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PurchaseListRequest $request, PurchaseList $purchaseList)
    {
        $data = $request->validated();

        try {
            $purchaseList->update($data);

            return $purchaseList;
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseList $purchaseList)
    {
        try {
            $purchaseList->delete();

            return response()->json([
                'message' => 'Lista de compras excluída com sucesso!',
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
