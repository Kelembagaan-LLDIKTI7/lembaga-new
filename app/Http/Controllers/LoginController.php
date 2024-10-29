<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('Login');
    }


    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)
            ->orWhere('nip', $request->email)
            ->first();
            
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            $request->session()->regenerate();

            return redirect()->intended('dashboard')->with('success', 'Login Berhasil');
        }

        return redirect()->back()->with('error', 'Email Dan Password Salah');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logout Berhasil');
    }
}
