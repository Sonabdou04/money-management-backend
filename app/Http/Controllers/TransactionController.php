<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Transaction::all(),
            'message' => 'Transactions retrieved successfully.'
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $transaction = Transaction::create(array_merge($validated, ['user_id' => $user->id]));
        
        return response()->json([
            'message' => 'Transaction created successfully.',
            'transaction' => $transaction
        ], 201);
    }

    public function show(Transaction $transaction)
    {
        return response()->json([
            'transaction' => $transaction,
            'message' => 'Transaction retrieved successfully.'
        ]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $user = $request->user();
        $validated = $request->validate([
            'amount' => 'sometimes|numeric',
            'type' => 'sometimes|in:income,expense',
            'category_id' => 'sometimes|exists:categories,id',
            'date' => 'sometimes|date',
            'description' => 'nullable|string',
        ]);

        $transaction->update(array_merge(['user_id' => $user->id], $validated));
        
        return response()->json([
            'message' => 'Transaction updated successfully.',
            'transaction' => $transaction
        ]);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return response()->json([
            'message' => 'Transaction deleted successfully.'
        ], 204);
    }
}
