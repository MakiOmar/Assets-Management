<?php

namespace App\Http\Controllers;

use App\Models\Liability;
use Illuminate\Http\Request;

class LiabilityController extends Controller
{
    public function index(Request $request)
    {
        $liabilities = Liability::where('user_id', $request->user()->id)->get();
        return response()->json($liabilities);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'amount' => 'required|numeric']);
        Liability::create($request->all());
        return response()->json(['message' => 'Liability added successfully']);
    }

    public function update(Request $request, Liability $liability)
    {
        $liability->update($request->all());
        return response()->json(['message' => 'Liability updated successfully']);
    }

    public function destroy(Liability $liability)
    {
        $liability->delete();
        return response()->json(['message' => 'Liability deleted successfully']);
    }
}
