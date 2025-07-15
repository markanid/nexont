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
        $company_logo = Company::where('type', 'company')->orderBy('id', 'DESC')->pluck('logo_path')->first();
        $company_logo = trim($company_logo, '"'); 

        return view('auth.login', ['company_logo' => $company_logo]);
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
        $hasUsers           = User::exists(); 
        $hasAdminUser       = User::where('role', 'Admin')->exists();
        $hasClientCompany   = Company::where('type', 'client')->exists();
        $clientCompanies    = Company::where('type', 'client')->get();
        $employees          = Employee::all();
        return view('auth.register', compact('hasUsers', 'hasClientCompany', 'clientCompanies', 'hasAdminUser', 'employees'));
    }

    public function registerProcess(Request $request){
        
        $request->merge([
            'name'          => $request->name ? trim($request->name) : null,
            'employee_name' => $request->employee_name ? trim($request->employee_name) : null,
        ]);

        $rules = [
            "email"     => 'required|email|unique:users',
            "password"  => 'required|min:8|confirmed',
            "role"      => 'required',
        ];

        if (in_array($request->role, ['Admin', 'Client'])) {
            $rules['name'] = 'required|string|max:255';
        } else {
            $rules['employee_name'] = 'required|string|max:255';
        }

        if ($request->role === 'Client') {
            $rules['company_id'] = 'required|exists:companies,id';
        }

        $messages = [
            'email.required'         => 'Please enter an email address.',
            'email.email'            => 'Please enter a valid email address.',
            'email.unique'           => 'This email is already registered.',
            'password.required'      => 'Please enter a password.',
            'password.min'           => 'Password must be at least 8 characters.',
            'password.confirmed'     => 'Passwords do not match.',
            'role.required'          => 'Please select a role.',
            'name.required'          => 'Please enter the user’s full name.',
            'employee_name.required' => 'Please select an employee from the list.',
            'company_id.required'    => 'Please select a client company.',
            'company_id.exists'      => 'The selected company is invalid.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->route('auth.registration')
                ->withInput()
                ->withErrors($validator);
        }

        $finalName = in_array($request->role, ['Admin', 'Client']) 
            ? $request->name 
            : $request->employee_name;

        $user = new User();
        $user->name     = $finalName;
        $user->email    = $request->email;
        $user->password = Hash::make($request->password);
        $user->role     = $request->role;

        if ($request->role === 'Client') {
            $user->company_id = $request->company_id;
        }

        $user->save();
        return redirect()->route('auth.login')->with('success', 'You have registered successfully.');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password'          => 'required',
            'new_password'              => 'required|min:8|confirmed',  // You can adjust the password requirements
            'new_password_confirmation' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            dd($validator->errors());
            return redirect()->route('users.changePasswordForm')
            ->withErrors($validator)
            ->withInput();
        }

        $user = Auth::user();

        // Check if the current password matches the user's current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('users.changePasswordForm')
            ->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the user's password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('company.index')->with('success', 'Your password has been changed successfully!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.login');
    }

    
}
