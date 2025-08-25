<?php

namespace App\Http\Controllers;

use App\Models\RecurringTransaction;
use Illuminate\Http\Request;

class RecurringTransactionController extends Controller
{
    public function index()
    {
        return response()->json(RecurringTransaction::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'next_due_date' => 'required|date',
        ]);

        $recurring = RecurringTransaction::create(array_merge(["user_id" => $request->user()->id], $validated));
        return response()->json($recurring, 201);
    }

    public function show(RecurringTransaction $recurringTransaction)
    {
        return response()->json($recurringTransaction);
    }

    public function update(Request $request, RecurringTransaction $recurringTransaction)
    {
        $validated = $request->validate([
            'amount' => 'sometimes|numeric',
            'type' => 'sometimes|in:income,expense',
            'category_id' => 'sometimes|exists:categories,id',
            'frequency' => 'sometimes|in:daily,weekly,monthly,yearly',
            'next_due_date' => 'sometimes|date',
        ]);

        $recurringTransaction->update(array_merge(["user_id" => $request->user()->id], $validated));
        return response()->json($recurringTransaction);
    }

    public function destroy(RecurringTransaction $recurringTransaction)
    {
        $recurringTransaction->delete();
        return response()->json(null, 204);
    }
}
