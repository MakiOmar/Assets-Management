<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        // Return JSON if the request is XHR (AJAX)
        if ($request->ajax()) {
            return response()->json(['categories' => $categories]);
        }

        // Otherwise, return the view for traditional requests
        return view('categories.index', compact('categories'));
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(['html' => view('partials.categories.create_form')->render()]);
        }

        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Asset,Liability',
        ]);

        $category = Category::create($request->all());

        // Return JSON response for XHR
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully.',
                'category' => $category,
            ]);
        }

        // Redirect for traditional requests
        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Request $request, Category $category)
    {
        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.categories.edit_form', compact('category'))->render(),
            ]);
        }

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Asset,Liability',
        ]);

        $category->update($request->all());

        // Return JSON response for XHR
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.',
                'category' => $category,
            ]);
        }

        // Redirect for traditional requests
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Request $request, Category $category)
    {
        $category->delete();

        // Return JSON response for XHR
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.',
            ]);
        }

        // Redirect for traditional requests
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
