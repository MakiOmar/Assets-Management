<?php

namespace App\Http\Controllers;

use App\Models\Liability;
use Illuminate\Http\Request;

class LiabilityController extends Controller
{
    public function index(Request $request)
    {
        $liabilities = Liability::where('user_id', $request->user()->id)->get();

        // XHR Request: Return JSON
        if ($request->ajax()) {
            return response()->json(['liabilities' => $liabilities]);
        }

        // HTTP Request: Return View
        return view('liabilities.index', compact('liabilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
        ]);

        $liability = Liability::create(array_merge($request->all(), ['user_id' => $request->user()->id]));

        // XHR Request: Return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Liability added successfully',
                'liability' => $liability,
            ]);
        }

        // HTTP Request: Redirect
        return redirect()->route('liabilities.index')->with('success', 'Liability added successfully.');
    }

    public function update(Request $request, Liability $liability)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
        ]);

        $liability->update($request->all());

        // XHR Request: Return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Liability updated successfully',
                'liability' => $liability,
            ]);
        }

        // HTTP Request: Redirect
        return redirect()->route('liabilities.index')->with('success', 'Liability updated successfully.');
    }

    public function destroy(Request $request, Liability $liability)
    {
        $liability->delete();

        // XHR Request: Return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Liability deleted successfully.',
            ]);
        }

        // HTTP Request: Redirect
        return redirect()->route('liabilities.index')->with('success', 'Liability deleted successfully.');
    }
}
