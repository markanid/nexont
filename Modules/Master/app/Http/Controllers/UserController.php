<?php

namespace Modules\Master\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\Master\app\Models\Company;
use Modules\Master\app\Models\User;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Modules\Member\app\Models\Employee;

class UserController extends Controller
{
    public function index() {   
        $data['users'] = User::latest('id')->get();
        $data['title']      = "Users";
        $data['page_title'] = "Users List";
        return view('master::users.index', $data); 
    }

    public function createOrEdit($id = null) {
        $data['user']               = $id ? User::findOrFail($id) : new User();
        $data['title']              = "Users";
        $data['page_title']         = $id ? "Edit User" : "Create User";
        $data['hasUsers']           = User::exists();
        $data['hasAdminUser']       = User::where('role', 'Admin')->exists();
        $data['hasClientCompany']   = Company::where('type', 'client')->exists();
        $data['clientCompanies']    = Company::where('type', 'client')->get();
        $data['employees']          = Employee::select('id', 'name')->get();
        return view('master::users.create', $data);
    }
    
    public function storeOrUpdate(Request $request)
    {
        $role = $request->input('role');
        $rules = [
            'email'       => 'nullable|email',
            'role'        => 'nullable|string|max:255',
            'company_id'  => $role === 'Client' ? 'required|string|max:255' : 'nullable|string|max:255',
            'avatar'      => 'nullable|image|mimes:jpg,png,jpeg|max:300000',
        ];

        // Conditional validation for name
        if (in_array($role, ['Admin', 'Client'])) {
            $rules['name'] = 'required|string|max:100';
        } else {
            $rules['employee_name'] = 'required|string|exists:employees,name';
        }

        $validated = $request->validate($rules);

        $name = in_array($role, ['Admin', 'Client']) ? $request->name : $request->employee_name;
        $user = User::findOrFail($request->id);
        $user->email      = $request->email;
        $user->role       = $request->role;
        $user->name       = $name;
        $user->company_id = $request->company_id;

        if ($request->hasFile('avatar')) {
            if ($user && $user->avatar) {
                Storage::disk('public')->delete('user_logos/' . $user->avatar);
            }
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName(); 
            $file->storeAs('user_logos', $filename, 'public'); 
            $user->avatar = $filename;
        }    
        $user->save();
        return redirect()->route('users.show', $user->id)->with('success', 'User details updated successfully.');
    }

    public function show($id) {
        $data['user']       = User::with('company')->findOrFail($id);
        $data['title']      = "Users";
        $data['page_title'] = "View User";
        return view('master::users.view', $data);
    }

    public function showProfile($id) {
        $data['user']       = User::with('company')->findOrFail($id);
        $data['title']      = "Users";
        $data['page_title'] = "View User";
        return view('master::users.viewprofile', $data);
    }

    public function showChangePasswordForm() {
        $id =    Auth::user()->id;
        $company_logo = Company::orderBy('created_at', 'DESC')->pluck('logo_path')->first();
        $company_logo = trim($company_logo, '"'); 
        $user = User::findOrFail($id);
        return view('master::users.changepassword', compact('user', 'company_logo'));
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password'          => 'required',
            'new_password'              => 'required|min:8|confirmed',  // You can adjust the password requirements
            'new_password_confirmation' => 'required|min:8',
        ]);

        if ($validator->fails()) {
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

        return redirect()->route('logout')->with('success', 'Your password has been changed successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (!empty($user->avatar) && Storage::disk('public')->exists('user_logos/' . $user->avatar)) {
            Storage::disk('public')->delete('user_logos/' . $user->avatar);
        }
        Storage::delete('public/user_logos/' . $user->avatar);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Record deleted successfully');
    }
     
}
