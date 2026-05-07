<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    // Trang login
    public function showLogin()
    {
        return view('auth.login');
    }


    // Xử lý login
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ],[
        'email.email' => 'Email phải có dấu @'
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        if (Auth::user()->vai_tro === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('booking.home');
    }

    return back()->with('error','Sai email hoặc mật khẩu');
}
    // Trang đăng ký
    public function register()
    {
        return view('auth.register');
    }


    // Xử lý đăng ký
    public function registerStore(Request $request)
    {

        $request->validate([
            'ho_ten' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ],[
            'ho_ten.required' => 'Vui lòng nhập họ tên',

            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email phải có dấu @',
            'email.unique' => 'Email đã tồn tại',

            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu ít nhất 6 ký tự'
        ]);

        User::create([
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'vai_tro' => 'user'
        ]);

        return redirect()->route('login')
        ->with('success','Đăng ký thành công');
    }


    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // =========================
// GOOGLE LOGIN
// =========================

public function redirectToGoogle()
{
    return Socialite::driver('google')->redirect();
}

public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // tìm user theo email
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // tạo user mới
            $user = User::create([
                'ho_ten' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => Hash::make('12345678'),
                'vai_tro' => 'user'
            ]);
        }

        Auth::login($user);

        // phân quyền giống login thường
        if ($user->vai_tro === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('booking.home');

    } catch (\Exception $e) {
        return redirect()->route('login')
            ->with('error', 'Đăng nhập Google thất bại!');
    }
}

}