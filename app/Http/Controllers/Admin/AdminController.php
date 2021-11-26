<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'password' => 'required',
            'cpassword' => 'required|same:password'
        ],[
            'cpassword.required' => 'The confirm password field is required.',
            'cpassword.same' => 'The confirm password and password must match.'
        ]);

        $admin = new Admin();

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->password = Hash::make($request->password);

        $data = $admin->save();

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
            'email' => 'required|email|exists:admins,email',
            'password' => 'required',
        ],[
            'email.exists' => 'This email is not registered in our system'
        ]
        );

        $check = $request->only('email', 'password');

        if(Auth::guard('admin')->attempt($check))
        {
            return redirect()->route('admin.home')->with('success', 'Welcome ' . Auth::guard('admin')->user()->name);
        }
        else
        {
            return redirect()->back()->with('error', 'Login Failed');
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        //return redirect()->route('user.login')->with('success', 'Logged out Successfully');
        return redirect('/');
    }
}
