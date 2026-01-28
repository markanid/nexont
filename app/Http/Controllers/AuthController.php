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
        $adminExists    = Employee::where('designation', 'Admin')->exists();
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

        if (!$validator->passes()) {
            return redirect()->route('login')
                ->withInput()
                ->withErrors($validator);
        }
        $employee = Employee::where('email', $request->email)->first();

        $credentials = $request->only('email', 'password');
        
        // 1. Try user guard (default web)
        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();
            session(['id' => $user->id, 'type' => 'user']);
            return redirect()->route('dashboard');
        }

        // 2. Try employee guard
        if (Auth::guard('employee')->attempt([
            'email' => $request->email,
            'password' => $request->password,
            'status' => 1
        ])) {
            $employee = Auth::guard('employee')->user();
            session(['id' => $employee->id, 'type' => 'employee']);
            return redirect()->route('dashboard'); // or employee dashboard
        }
        // 3. If neither succeeds
        return redirect()->route('login')->with('error', 'Email or Password is incorrect');

    }

    public function registration(){
        return view('auth.register');
    }

    public function registerProcess(Request $request){

        $adminExists = Employee::where('designation', 'Admin')->exists();
        if ($adminExists) {
            return redirect()->route('login')
                ->with('error', 'An admin account already exists. You cannot register another one.');
        }

        $rules = [
            "email"     => 'required|email|unique:employees,email',
            "password"  => 'required|min:8|confirmed',
            'name'      => 'required|string|max:255',
            // 'terms'     => 'accepted'
        ];
        $messages = [
            'email.required'        => 'Please enter an email address.',
            'email.email'           => 'Please enter a valid email address.',
            'email.unique'          => 'This email is already registered.',
            'password.required'     => 'Please enter a password.',
            'password.min'          => 'Password must be at least 8 characters.',
            'password.confirmed'    => 'Passwords do not match.',
            'name.required'         => 'Please enter the user’s full name.',
            // 'terms.accepted'        => 'You must agree to the terms and conditions.'
        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->route('auth.registration')
                ->withInput()
                ->withErrors($validator);
        }
        try {
            $employee = new Employee();
            $employee->employee_code = Employee::getEmployeeCode();
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->password = Hash::make($request->password);
            $employee->designation = 'Admin';
            $employee->status = 1; // Active by default
            $employee->save();
        } catch (\Exception $e) {
            return redirect()->route('auth.registration')->with('error', $e->getMessage());
        }
        return redirect()->route('login')->with('success', 'You have registered successfully.');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        Auth::guard('employee')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    }

    
}
