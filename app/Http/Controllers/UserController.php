<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users = User::where('role' , '0')->get();

        return view('users.index')->with(['users'=>$users]);
    }
    public function destroy(User $user){
        $user->delete();
        return to_route('users.index');
    }
}
