<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function Index(){
        return view('frontend.index');
    }

    public function UserProfile(){
        $userData = User::find(Auth::user()->id);
        return view('frontend.dashboard.edit_profile', compact('userData'));
    }
}
