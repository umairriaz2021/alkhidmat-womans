<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Auth;
class UserController extends Controller
{

  public function register()
{
    // Agar user pehle se logged in hai...
    if (auth()->check()) {
        // ...aur wo Super Admin hai, toh use Dashboard par bhej do
        if (auth()->user()->hasRole('super_admin')) {
            return redirect()->route('admin.dashboard');
        }
    }

    // Agar user logged in NAHI hai, tabhi use login view dikhao
    return view('admin.auth.register'); 
}
    public function login()
{
    // Agar user pehle se logged in hai...
    if (auth()->check()) {
        // ...aur wo Super Admin hai, toh use Dashboard par bhej do
        if (auth()->user()->hasRole('super_admin')) {
            return redirect()->route('admin.dashboard');
        }
        
        // Agar logged in hai par admin nahi hai, toh use website ke home page par bhej do
        return redirect('/admin/login');
    }

    // Agar user logged in NAHI hai, tabhi use login view dikhao
    return view('admin.auth.login'); 
}

    public function store(Request $request)
    {
         
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            // Role check: Agar Super Admin hai toh dashboard pe bhejo
            if (auth()->user()->hasRole('super_admin')) {
                return redirect()->intended(route('admin.dashboard'));
            }

            // Agar koi aur role hai (jaise editor) toh wahan bhejein ya logout kar dein
            return redirect()->intended('/admin/login'); 
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }
    public function index()
    {
        $users = User::with('roles')->get(); // Saare users unke roles ke saath
        return view('admin.users.index', compact('users'));
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

  
}
