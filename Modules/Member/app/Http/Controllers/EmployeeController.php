<?php

namespace Modules\Member\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $employee = $id ? Employee::findOrFail($id) : new Employee();
        $employee_code = $id ? $employee->employee_code : Employee::getEmployeeCode();
        $data['page_title'] = $id ? "Edit Employee" : "Create Employee";
        $data['employee']   = $employee;
        $data['employee_code']   = $employee_code;
        return view('member::employees.create', $data);
    }

    public function storeOrUpdate(Request $request)
    {
        $request->merge([
            'date_of_birth' => $request->date_of_birth ? Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d') : null,
            'joining_date'  => $request->joining_date ? Carbon::createFromFormat('d/m/Y', $request->joining_date)->format('Y-m-d') : null,
        ]);

        $rules = [
            'employee_code'      => 'required|string|max:255|unique:employees,employee_code,' . $request->id,
            'name'               => 'required|string|max:255',
            'gender'             => 'nullable|in:Male,Female,Other',
            'date_of_birth'      => 'nullable|date',
            'phone'              => 'nullable|string|max:20',
            'email'              => 'nullable|email|max:255',
            'address'            => 'nullable|string|max:500',
            'designation'        => 'nullable|string|max:255',
            'joining_date'       => 'nullable|date',
            'status'             => 'nullable|in:Active,Inactive',
            'image'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
        $data['employee']   = Employee::findOrFail($id);
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
