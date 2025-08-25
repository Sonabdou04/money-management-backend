<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Budget::all(),
            'message' => 'Budgets retrieved successfully.'
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'amount_limit' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $budget = Budget::create(array_merge(["user_id" => $user->id], $validated));

        return response()->json([
            'message' => 'Budget created successfully.',
            'budget' => $budget
        ], 201);
    }

    public function show(Budget $budget)
    {
        return response()->json([
            'budget' => $budget,
            'message' => 'Budget retrieved successfully.'
        ]);
    }

    public function update(Request $request, Budget $budget)
    {
        $user = $request->user();

        $validated = $request->validate([
            'amount_limit' => 'sometimes|numeric',
            'category_id' => 'sometimes|exists:categories,id',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
        ]);

        $budget->update(array_merge(["user_id" => $user->id], $validated));
        return response()->json([
            'message' => 'Budget updated successfully.',
            'budget' => $budget
        ]);
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();
        return response()->json([
            'message' => 'Budget deleted successfully.'
        ], 204);
    }
}
