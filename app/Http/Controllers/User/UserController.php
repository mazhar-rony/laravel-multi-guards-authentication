<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'cpassword' => 'required|same:password'
        ],[
            'cpassword.required' => 'The confirm password field is required.',
            'cpassword.same' => 'The confirm password and password must match.'
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password =Hash::make($request->name);

        $data = $user->save();

        if($data)
        {
            return redirect()->back()->with('success', 'You have successfully registered');
        }
        else
        {
            return redirect()->back()->with('error', 'Registration Failed');
        }
    }
}
