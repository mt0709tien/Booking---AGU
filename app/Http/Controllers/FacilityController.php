<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Category;

class FacilityController extends Controller
{

    public function index(Request $request)
{
    $categories = Category::with(['facilities' => function ($query) use ($request) {

        $query->withCount('roomBookings')
              ->withCount('sportBookings');

        // tìm theo tên cơ sở vật chất
        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // lọc theo danh mục
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

    }])->get();

    return view('facilities.index', compact('categories'));
}

    // Form thêm
    public function create()
    {
        $categories = Category::all();

        return view('facilities.create', compact('categories'));
    }

    // Lưu
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->only(['name','category_id','description']);

        // upload hình
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();

            $file->move(public_path('images'), $filename);

            $data['image'] = $filename;
        }

        Facility::create($data);

        return redirect()->route('admin.facilities')
            ->with('success','Thêm thành công');
    }

    // Form sửa
    public function edit($id)
    {
        $facility = Facility::findOrFail($id);
        $categories = Category::all();

        return view('facilities.edit', compact('facility','categories'));
    }

    // Cập nhật
    public function update(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->only(['name','category_id','description']);

        // upload hình mới
        if ($request->hasFile('image')) {

            // xóa ảnh cũ nếu có
            if ($facility->image && file_exists(public_path('images/'.$facility->image))) {
                unlink(public_path('images/'.$facility->image));
            }

            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();

            $file->move(public_path('images'), $filename);

            $data['image'] = $filename;
        }

        $facility->update($data);

        return redirect()->route('admin.facilities')
            ->with('success','Cập nhật thành công');
    }

    // Xóa
    public function delete($id)
    {
        $facility = Facility::findOrFail($id);

        // xóa ảnh nếu có
        if ($facility->image && file_exists(public_path('images/'.$facility->image))) {
            unlink(public_path('images/'.$facility->image));
        }

        $facility->delete();

        return redirect()->route('admin.facilities')
            ->with('success','Xóa thành công');
    }

}