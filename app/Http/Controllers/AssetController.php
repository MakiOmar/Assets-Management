<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $assets = Asset::getAssets($request);
        // HTTP Request: Return View
        return view('assets.index', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|numeric',
        ]);

        $asset = Asset::create(array_merge($request->all(), ['user_id' => $request->user()->id]));

        // XHR Request: Return JSON
        if ($request->isXhr()) {
            return response()->json([
                'success' => true,
                'message' => 'Asset added successfully',
                'asset' => $asset,
            ]);
        }

        // HTTP Request: Redirect
        return redirect()->route('manage-assets.index')->with('success', 'Asset added successfully.');
    }

    public function update(Request $request, Asset $asset)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|numeric',
        ]);

        $asset->update($request->all());

        // XHR Request: Return JSON
        if ($request->isXhr()) {
            return response()->json([
                'success' => true,
                'message' => 'Asset updated successfully',
                'asset' => $asset,
            ]);
        }

        // HTTP Request: Redirect
        return redirect()->route('manage-assets.index')->with('success', 'Asset updated successfully.');
    }

    public function destroy(Request $request, Asset $asset)
    {
        $asset->delete();

        // XHR Request: Return JSON
        if ($request->isXhr()) {
            return ''; // No Content
        }

        // HTTP Request: Redirect
        return redirect()->route('manage-assets.index')->with('success', 'Asset deleted successfully.');
    }
}
