<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        $user->password = Hash::make($request->password);

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

    public function doLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $check = $request->only('email', 'password');

        if(Auth::guard('web')->attempt($check))
        {
            return redirect()->route('user.home')->with('success', 'Welcome ' . Auth::guard('web')->user()->name);
        }
        else
        {
            return redirect()->back()->with('error', 'Login Failed');
        }
    }

    public function logout()
    {
        Auth::guard('web')->logout();

        //return redirect()->route('user.login')->with('success', 'Logged out Successfully');
        return redirect('/');
    }
}
