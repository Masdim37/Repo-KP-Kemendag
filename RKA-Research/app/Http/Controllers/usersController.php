<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class usersController extends Controller
{
        public function ShowLogin()
    {
        return view('user.login');
    }

    public function login(Request $request)
    {
        $usernameInput = $request->input('username');
        $passwordInput = $request->input('password');

        $user = User::where('username', $usernameInput)->first();

        if ($user) {
            // Perbaikan: gunakan check (huruf kecil)
            if (Hash::check($passwordInput, $user->password)) { 
                Session::put('user_id', $user->userID);
                Session::put('user_name', $user->nameUser);             
                return redirect('/Homepage');
            }
        }

        return back()->with('error', 'Username atau Password salah!');
    }
}
