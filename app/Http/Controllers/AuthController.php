<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//include these
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerPage(){
        return view('auth.register');
    }
    public function register(Request $request){
        $validated = $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ],[
            'username.required' => 'Dont leave it empty please',
        ]);

        $user = new User();
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('loginPage')->with(['success' => 'Register Success']);
    }
    public function loginPage(){
        return view('auth.login');
    }
    public function login(Request $request){
        $validated = $request->validate([
            'username' => 'required|exists:users',
            'password' => 'required',
        ]);

        $user = User::where([
            'username' => $request->username,
        ])->first();

        if($user && Hash::check($request->password, $user->password)){
           //user is validated
            Auth::login($user);
            switch($user->role){
                case '0':
                    return redirect()->route('gallery.index');
                    break;
                case '1':
                    return redirect()->route('users.index');
                    break;
                default:break;
            }
        }
            return back()->withErrors(['username' => 'Invalid Username or Password']);
    }
}
