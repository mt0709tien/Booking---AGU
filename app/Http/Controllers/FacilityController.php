<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Category;

class FacilityController extends Controller
{

    // Danh sách cơ sở vật chất
    public function index()
    {
        $facilities = Facility::with('category')->get();

        return view('facilities.index', compact('facilities'));
    }


    // Trang thêm
    public function create()
    {
        $categories = Category::all();

        return view('facilities.create', compact('categories'));
    }


    // Lưu dữ liệu
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();

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


    // Trang sửa
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

        $data = $request->all();

        if ($request->hasFile('image')) {

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

        $facility->delete();

        return redirect()->route('admin.facilities')
            ->with('success','Xóa thành công');
    }

}