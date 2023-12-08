<?php

namespace App\Http\Controllers;

use App\Http\Requests\VariableExpenseRequest;
use App\Models\VariableExpense;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VariableExpenseController extends Controller
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
        return VariableExpense::query()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VariableExpenseRequest $request)
    {
        $data = $request->validated();

        try {
            return VariableExpense::query()->create($data);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VariableExpense $variableExpense)
    {
        return $variableExpense;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VariableExpenseRequest $request, VariableExpense $variableExpense)
    {
        $data = $request->validated();

        try {
            $variableExpense->update($data);

            return $variableExpense;
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VariableExpense $variableExpense)
    {
        try {
            $variableExpense->delete();

            return response('', Response::HTTP_NO_CONTENT);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
