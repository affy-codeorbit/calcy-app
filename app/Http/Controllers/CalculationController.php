<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use Illuminate\Http\Request;

class CalculationController extends Controller
{
    // GET /api/calculations — most recent first, capped at 100
    public function index()
    {
        return Calculation::orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->limit(100)
            ->get();
    }

    // POST /api/calculations
    public function store(Request $request)
    {
        $validated = $request->validate([
            'expression' => ['required', 'string', 'max:255'],
            'result' => ['required', 'string', 'max:50'],
        ]);

        $calculation = Calculation::create($validated);

        return response()->json($calculation, 201);
    }

    // DELETE /api/calculations/{calculation}
    public function destroy(Calculation $calculation)
    {
        $calculation->delete();

        return response()->json(['success' => true]);
    }

    // DELETE /api/calculations
    public function clear()
    {
        Calculation::query()->delete();

        return response()->json(['success' => true]);
    }
}
