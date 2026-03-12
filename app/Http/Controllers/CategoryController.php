<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($id)
    {
        $category = Category::with(['facilities' => function ($q) {
            $q->withCount('bookings');
        }])->findOrFail($id);

        return view('category.show', compact('category'));
    }


    public function index()
    {
        $categories = Category::all();
        return view('admin.categories', compact('categories'));
    }


    public function create()
    {
        return view('category.create');
    }


    public function store(Request $request)
    {
        Category::create([
            'name' => $request->name,
            'price_morning' => $request->price_morning,
            'price_afternoon' => $request->price_afternoon,
            'price_evening' => $request->price_evening
        ]);

        return redirect()->route('admin.categories');
    }


    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('category.edit', compact('category'));
    }


    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $category->update([
            'name' => $request->name,
            'price_morning' => $request->price_morning,
            'price_afternoon' => $request->price_afternoon,
            'price_evening' => $request->price_evening
        ]);

        return redirect()->route('admin.categories');
    }


    public function delete($id)
    {
        Category::findOrFail($id)->delete();

        return redirect()->route('admin.categories');
    }
}