<?php

namespace App\Http\Controllers;

use App\Http\Requests\FixedExpenseRequest;
use App\Models\FixedExpense;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FixedExpenseController extends Controller
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
        return FixedExpense::query()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FixedExpenseRequest $request)
    {
        $data = $request->validated();

        try {
            return FixedExpense::query()->create($data);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Erro ao cadastrar despesa fixa',
                'error' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FixedExpense $fixedExpense)
    {
        return $fixedExpense;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FixedExpenseRequest $request, FixedExpense $fixedExpense)
    {
        $data = $request->validated();

        try {
            $fixedExpense->update($data);

            return $fixedExpense;
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FixedExpense $fixedExpense)
    {
        try {
            $fixedExpense->delete();

            return response('', Response::HTTP_NO_CONTENT);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
