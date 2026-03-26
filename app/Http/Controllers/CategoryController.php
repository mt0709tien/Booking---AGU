<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Hiển thị danh sách + tìm kiếm
    public function index(Request $request)
    {
        $query = Category::query();

        // Tìm kiếm theo tên
        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $categories = $query->paginate(10);

        return view('admin.categories', compact('categories'));
    }

    // Hiển thị form thêm
    public function create()
    {
        return view('category.create'); // hoặc admin.categories.create
    }

    // Lưu dữ liệu
    public function store(Request $request)
    {
        Category::create([
            'name' => $request->name,
            'price_morning' => $request->price_morning,
            'price_afternoon' => $request->price_afternoon,
            'price_evening' => $request->price_evening
        ]);

        return redirect()->route('admin.categories')
                         ->with('success', 'Thêm danh mục thành công');
    }

    // Hiển thị chi tiết
    public function show($id)
    {
        $category = Category::with(['facilities' => function ($q) {
            $q->withCount('bookings');
        }])->findOrFail($id);

        return view('category.show', compact('category'));
    }

    // Hiển thị form sửa
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('category.edit', compact('category'));
    }

    // Cập nhật dữ liệu
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $category->update([
            'name' => $request->name,
            'price_morning' => $request->price_morning,
            'price_afternoon' => $request->price_afternoon,
            'price_evening' => $request->price_evening
        ]);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Cập nhật thành công');
    }

    // Xóa dữ liệu
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();

        return redirect()->route('admin.categories')
                         ->with('success', 'Xóa thành công');
    }
}