<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Master\app\Models\Company;
use Modules\Master\app\Models\User;
use Modules\Member\app\Models\Employee;

class AuthController extends Controller
{
    public function getLogin(){
        $adminExists    = User::where('role', 'Admin')->exists();
        $company_logo   = Company::where('type', 'company')->orderBy('id', 'DESC')->pluck('logo_path')->first();
        $company_logo   = trim($company_logo, '"'); 
        return view('auth.login', ['company_logo' => $company_logo, 'adminExists' => $adminExists]);
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email"             => 'required|email',
            "password"          => 'required'
        ]);

        if ($validator->passes()) {
             
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                session(['id' => $user->id]);
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('auth.login')->with('error', 'Email or Password is incorrect');
            }
        } else {
            return redirect()->route('auth.login')
                ->withInput()
                ->withErrors($validator);
        }
    }

    public function registration(){
        return view('auth.register');
    }

    public function registerProcess(Request $request){

        $adminExists = User::where('role', 'Admin')->exists();
        if ($adminExists) {
            return redirect()->route('auth.login')
                ->with('error', 'An admin account already exists. You cannot register another one.');
        }

        $rules = [
            "email"     => 'required|email|unique:users',
            "password"  => 'required|min:8|confirmed',
            'name'      => 'required|string|max:255',
            'terms'     => 'accepted'
        ];

        $messages = [
            'email.required'        => 'Please enter an email address.',
            'email.email'           => 'Please enter a valid email address.',
            'email.unique'          => 'This email is already registered.',
            'password.required'     => 'Please enter a password.',
            'password.min'          => 'Password must be at least 8 characters.',
            'password.confirmed'    => 'Passwords do not match.',
            'name.required'         => 'Please enter the user’s full name.',
            'terms.accepted'        => 'You must agree to the terms and conditions.'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->route('auth.registration')
                ->withInput()
                ->withErrors($validator);
        }

        $user = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = Hash::make($request->password);
        $user->role     = 'Admin'; 

        $user->save();
        return redirect()->route('auth.login')->with('success', 'You have registered successfully.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.login');
    }

    
}
