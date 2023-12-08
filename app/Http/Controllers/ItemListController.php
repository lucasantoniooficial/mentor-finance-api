<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemListRequest;
use App\Models\ItemList;
use App\Models\PurchaseList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PurchaseList $purchaseList)
    {
        return $purchaseList->itemLists()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemListRequest $request, PurchaseList $purchaseList)
    {
        $data = $request->validated();

        try {
            return $purchaseList->itemLists()->create($data);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseList $purchaseList, ItemList $itemList)
    {
        return $itemList;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItemListRequest $request, PurchaseList $purchaseList, ItemList $itemList)
    {
        $data = $request->validated();

        try {
            $itemList->update($data);
            return $itemList;
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseList $purchaseList, ItemList $itemList)
    {
        try {
            $itemList->delete();
            return response('', Response::HTTP_NO_CONTENT);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
