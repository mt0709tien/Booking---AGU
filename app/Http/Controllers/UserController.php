<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Danh sách va tìm kiếm
    public function index(Request $request)
    {
        $query = User::query();

        //  Tìm kiếm
        if ($request->keyword) {
            $query->where(function ($q) use ($request) {
                $q->where('ho_ten', 'like', '%' . $request->keyword . '%')
                  ->orWhere('email', 'like', '%' . $request->keyword . '%');
            });
        }

        //  Sắp xếp mới nhất
        $users = $query->latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    //  Form thêm
    public function create()
    {
        return view('users.create');
    }

    //  Lưu
    public function store(Request $request)
    {
        $request->validate([
            'ho_ten' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'vai_tro' => 'required|in:admin,user'
        ],[
            'email.email' => 'Email phải đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'password.min' => 'Mật khẩu tối thiểu 6 ký tự'
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

    //  Form sửa
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'ho_ten' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'vai_tro' => 'required|in:admin,user'
        ]);

        $data = [
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'vai_tro' => $request->vai_tro
        ];

        //  Nếu có nhập password thì mới update
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success','Cập nhật thành công');
    }

    //  Xóa
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // 🔥 Không cho xóa chính mình
        if ($user->id == auth()->id()) {
            return back()->with('error','Không thể xóa chính bạn');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success','Xóa thành công');
    }
    public function updateProfile(Request $request)
{
    $request->validate([
        'ho_ten' => 'required|max:255',
    ]);

    $user = auth()->user();

    $user->update([
        'ho_ten' => $request->ho_ten
    ]);

    return back()->with('success', 'Cập nhật thông tin thành công!');
}


public function updatePassword(Request $request)
{
    $request->validate([
        'old_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $user = auth()->user();

    // kiểm tra mật khẩu cũ
    if (!Hash::check($request->old_password, $user->password)) {
        return back()->with('error', 'Mật khẩu cũ không đúng');
    }

    $user->update([
        'password' => bcrypt($request->new_password)
    ]);

    return back()->with('success', 'Đổi mật khẩu thành công!');
}
}