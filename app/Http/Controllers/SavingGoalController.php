<?php

namespace App\Http\Controllers;

use App\Models\SavingGoal;
use Illuminate\Http\Request;

class SavingGoalController extends Controller
{
    public function index()
    {
        return response()->json(SavingGoal::all());
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string',
            'target_amount' => 'required|numeric|min:1',
            'saved_amount' => 'nullable|numeric|min:0',
            'deadline' => 'required|date|after:today',
        ]);

        $goal = SavingGoal::create(array_merge(["user_id" => $user->id], $validated));
        return response()->json($goal, 201);
    }

    public function show(SavingGoal $savingGoal)
    {
        return response()->json($savingGoal);
    }

    public function update(Request $request, SavingGoal $savingGoal)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'target_amount' => 'sometimes|numeric|min:1',
            'saved_amount' => 'sometimes|numeric|min:0|lte:target_amount',
            'deadline' => 'sometimes|date|after:today',
        ]);

        $savingGoal->update(array_merge(["user_id" => $user->id], $validated));
        return response()->json($savingGoal);
    }

    public function destroy(SavingGoal $savingGoal)
    {
        $savingGoal->delete();
        return response()->json(null, 204);
    }
}
