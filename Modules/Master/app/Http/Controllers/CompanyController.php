<?php

namespace Modules\Master\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\Master\app\Models\Company;

class CompanyController extends Controller
{
    public function index()
    {
        $data['companies']  = Company::latest('id')->get();
        $data['title']      = "Company";
        $data['page_title'] = "Company List";
        return view('master::companies.index', $data); 
    }

    public function createOrEdit($id = null)
    {
        $data['company'] = $id ? Company::findOrFail($id) : new Company();
        $data['title']      = "Company";
        $data['page_title'] = $id ? "Edit Company" : "Create Company";
        return view('master::companies.create', $data);
    }

    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'address'   => 'nullable|string',
            'phone'     => 'nullable|string|max:15',
            'email'     => 'nullable|email',
            'website'   => 'nullable|string|max:255',
            'type'      => 'nullable|string|max:255',
            'client_id' => $request->type === 'client' ? 'required|string|max:255' : 'nullable|string|max:255',
            'logo_path' => 'nullable|image|mimes:jpg,png,jpeg|max:300000' 
        ]);
        
        $isNew = empty($request->id);
        $company = Company::find($request->id);

        if ($request->hasFile('logo_path')) {
            if ($company && $company->logo_path) {
                Storage::disk('public')->delete('company_logos/' . $company->logo_path);
            }
            $file = $request->file('logo_path');
            $filename = time() . '_' . $file->getClientOriginalName(); 
            $file->storeAs('company_logos', $filename, 'public'); 
            $validated['logo_path'] = $filename; 
        }    
        $company = Company::updateOrCreate(
            ['id' => $request->id], 
            $validated
        );

        if ($company) {
            return $isNew
                ? redirect()->route('companies.index')->with('success', 'Company created successfully.')
                : redirect()->route('companies.show', $company->id)->with('success', 'Company details updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update company details.');
        }
    }

    public function show($id)
    {
        $data['title']      = "Company";
        $data['page_title'] = "View Company";
        $data['company']    = Company::findOrFail($id);
        return view('master::companies.view',$data);
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        if (!empty($company->logo_path) && Storage::disk('public')->exists('company_logos/' . $company->logo_path)) {
            Storage::disk('public')->delete('company_logos/' . $company->logo_path);
        }
        Storage::delete('public/company_logos/' . $company->logo_path);
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Record deleted successfully');
    }
}
