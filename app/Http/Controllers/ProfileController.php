<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required',
            'phone' => 'required'
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();

        return back()->with('success', 'Cập nhật thành công!');
    }

    public function changePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('error', 'Mật khẩu cũ không đúng!');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}