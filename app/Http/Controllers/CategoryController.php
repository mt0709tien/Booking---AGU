<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Danh sách + tìm kiếm
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $categories = $query->latest()->paginate(10);

        return view('admin.categories', compact('categories'));
    }

    // Form thêm
    public function create()
    {
        return view('category.create');
    }

    // Lưu
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:room,sport',
            'price_morning' => 'nullable|numeric',
            'price_afternoon' => 'nullable|numeric',
            'price_evening' => 'nullable|numeric',
            'price_hour' => 'nullable|numeric',
        ]);

        Category::create([
            'name' => $request->name,
            'type' => $request->type,

            // room
            'price_morning' => $request->type == 'room' ? $request->price_morning : null,
            'price_afternoon' => $request->type == 'room' ? $request->price_afternoon : null,
            'price_evening' => $request->type == 'room' ? $request->price_evening : null,

            // sport
            'price_hour' => $request->type == 'sport' ? $request->price_hour : null,
        ]);

        return redirect()->route('admin.categories')
            ->with('success', 'Thêm danh mục thành công');
    }

    // Chi tiết
   public function show($id)
{
    $category = Category::with(['facilities' => function ($q) {
        $q->withCount('roomBookings')
          ->withCount('sportBookings');
    }])->findOrFail($id);

    return view('category.show', compact('category'));
}

    // Form sửa
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('category.edit', compact('category'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:room,sport',
            'price_morning' => 'nullable|numeric',
            'price_afternoon' => 'nullable|numeric',
            'price_evening' => 'nullable|numeric',
            'price_hour' => 'nullable|numeric',
        ]);

        $category->update([
            'name' => $request->name,
            'type' => $request->type,

            'price_morning' => $request->type == 'room' ? $request->price_morning : null,
            'price_afternoon' => $request->type == 'room' ? $request->price_afternoon : null,
            'price_evening' => $request->type == 'room' ? $request->price_evening : null,

            'price_hour' => $request->type == 'sport' ? $request->price_hour : null,
        ]);

        return redirect()->route('admin.categories')
            ->with('success', 'Cập nhật thành công');
    }

    // Xóa
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();

        return redirect()->route('admin.categories')
            ->with('success', 'Xóa thành công');
    }
    
}