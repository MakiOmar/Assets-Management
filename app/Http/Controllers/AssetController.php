<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $assets = Asset::where('user_id', $request->user()->id)->get();
        return response()->json($assets);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'value' => 'required|numeric']);
        Asset::create($request->all());
        return response()->json(['message' => 'Asset added successfully']);
    }

    public function update(Request $request, Asset $asset)
    {
        $asset->update($request->all());
        return response()->json(['message' => 'Asset updated successfully']);
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return response()->json(['message' => 'Asset deleted successfully']);
    }
}
