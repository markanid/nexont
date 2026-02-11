<?php

namespace Modules\Member\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Modules\Member\app\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $data['employees']  = Employee::latest('id')->get();
        $data['page_title'] = "Employee List";
        $data['title']      = "Employees";
        return view('member::employees.index', $data);  
    }

    public function createOrEdit($id = null)
    {
        $employee           = $id ? Employee::findOrFail($id) : new Employee();
        $isEdit             = isset($employee->id);
        $isAdminEdit        = $isEdit && $employee->designation === 'Admin';
        $employee_code      = $id ? $employee->employee_code : Employee::getEmployeeCode();

        $adminId = Employee::where('designation', 'Admin')->value('id');
        $data['pmoUsers'] = Employee::where('designation', 'PMO')->get();
        $data['pmUsers']  = Employee::where('designation', 'Project Manager')->get();
        $data['adminId']  = $adminId;

        $data['page_title'] = $id ? "Edit Employee" : "Create Employee";
        $data['isEdit']         = $isEdit;
        $data['isAdminEdit']    = $isAdminEdit;
        $data['employee']       = $employee;
        $data['employee_code']  = $employee_code;
        return view('member::employees.create', $data);
    }

    public function storeOrUpdate(Request $request)
    {
        $rules = [
            'employee_code'      => 'required|string|max:255|unique:employees,employee_code,' . $request->id,
            'name'               => 'required|string|max:255',
            'phone'              => 'nullable|string|max:20',
            'email'              => 'nullable|email|max:255',
            'designation'        => 'nullable|string|in:Admin,Project Manager,PMO,Sales Manager,Accountant,Employee',
            'target'            => 'nullable|numeric|min:0',
            'reporting_to'      => 'nullable|exists:employees,id',
            'status'             => 'nullable|in:Active,Inactive',
            'image'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => $request->id
            ? 'nullable|min:6|confirmed'   // edit → optional
            : 'required|min:6|confirmed',  // create → required
        ];
        $validated = $request->validate($rules);
        $isNew = empty($request->id);
        $employee = Employee::find($request->id);
        
        if ($request->hasFile('image')) {
            if ($employee && $employee->image) {
                Storage::disk('public')->delete('employee_logos/' . $employee->image);
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName(); 
            $file->storeAs('employee_logos', $filename, 'public'); 
            $validated['image'] = $filename; 
        }  

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']); // don't overwrite existing password
        }

        $adminId = Employee::where('designation', 'Admin')->value('id');

        if (in_array($validated['designation'], ['Admin', 'PMO'])) {
            $validated['reporting_to'] = $adminId;
        }

        $employee = Employee::updateOrCreate(
            ['id' => $request->id ?? null],
            $validated
        );
    
        if ($employee) {
            return $isNew
                ? redirect()->route('employees.index')->with('success', 'Employee  created successfully.')
                : redirect()->route('employees.show', $employee->id)->with('success', 'Employee details updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update employee details.');
        }
    }

    public function show($id)
    {
        $data['title'] = "Employee";
        $data['page_title'] = "Employee Details";
        $data['employee']   = Employee::with('reportingTo')->findOrFail($id);
        return view('member::employees.view', $data);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        if (!empty($employee->image) && Storage::disk('public')->exists('employee_logos/' . $employee->image)) {
            Storage::disk('public')->delete('employee_logos/' . $employee->image);
        }
        Storage::delete('public/employee_logos/' . $employee->image);
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Record deleted successfully');
    }
}
