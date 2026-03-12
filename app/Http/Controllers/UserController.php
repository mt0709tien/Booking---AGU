<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Hiển thị form thêm người dùng
    public function create()
    {
        return view('users.create');
    }

     public function store(Request $request)
{

    $request->validate([
        'ho_ten' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'vai_tro' => 'required'
    ],[
        'email.email' => 'Email phải đúng định dạng',
        'email.unique' => 'Email đã tồn tại'
    ]);

    User::create([
        'ho_ten' => $request->ho_ten,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'vai_tro' => $request->vai_tro
    ]);

    return redirect()->route('users.index')
    ->with('success','Thêm người dùng thành công');
}
    // Hiển thị form sửa
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Cập nhật người dùng
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'vai_tro' => $request->vai_tro
        ]);

        return redirect()->route('users.index')->with('success','Cập nhật thành công');
    }

    // Xóa người dùng
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success','Xóa thành công');
    }
}
