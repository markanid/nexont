<?php

namespace Modules\Member\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Member\app\Models\Vendor;

class VendorController extends Controller
{
    public function index()
    {
        $data['vendors']    = Vendor::latest('id')->get();
        $data['page_title'] = "Vendor List";
        $data['title']      = "Vendors";
        return view('member::vendors.index', $data); 
    }

    public function createOrEdit($id = null)
    {
        $data['title']      = "Vendors";
        $data['page_title'] = $id ? "Edit Vendor" : "Create Vendor";
        $data['vendor']     = $id ? Vendor::findOrFail($id) : new Vendor();
        return view('member::vendors.create', $data);
    }

    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'company_name'  => 'required|string|max:255',
            'company_phone' => 'required|string|max:255|unique:vendors,company_phone,' . $request->id,
            'email'         => 'nullable|string|max:255',
            'rep_name'      => 'nullable|string|max:255',
            'rep_phone'     => 'nullable|string|max:255|unique:vendors,rep_phone,' . $request->id,
            'address'       => 'nullable|string|max:255',
            'website'       => 'nullable|string|max:255',
            'gst_number'    => 'nullable|string|max:255',
            'w9_status'     => 'required|in:yes,no',
            'w9_files.*'    => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:2048',
            'logo'          => 'nullable|image|mimes:jpg,png,jpeg|max:300000', 
        ]);

        $isNew = empty($request->id);
        $vendor = Vendor::find($request->id);
        
        if ($request->hasFile('logo')) {
            if ($vendor && $vendor->logo) {  
                Storage::disk('public')->delete('vendor_logos/' . $vendor->logo);
            }
            $file = $request->file('logo');
            $filename = $vendor->cp_name . '.' . $file->getClientOriginalExtension(); 
            $file->storeAs('vendor_logos', $filename, 'public'); 
            $validated['logo'] = $filename; 
        }  

        $existingFiles = [];

        if ($vendor && $vendor->w9_files) {
            $existingFiles = json_decode($vendor->w9_files, true) ?? [];
        }

        if ($request->filled('deleted_w9_files')) {

            $filesToDelete = json_decode($request->deleted_w9_files, true) ?? [];

            foreach ($filesToDelete as $file) {
                if (Storage::disk('public')->exists('vendor_w9/' . $file)) {
                    Storage::disk('public')->delete('vendor_w9/' . $file);
                }
            }

            $existingFiles = array_values(array_diff($existingFiles, $filesToDelete));
            $vendor->w9_files = !empty($existingFiles)
                ? json_encode($existingFiles)
                : null;

            // Optional: update status if no files left
            if (empty($existingFiles)) {
                $vendor->w9_status = 'no';
            }

            $vendor->save();
        }

        if ($request->w9_status === 'no') {
            foreach ($existingFiles as $file) {
                if (Storage::disk('public')->exists('vendor_w9/' . $file)) {
                    Storage::disk('public')->delete('vendor_w9/' . $file);
                }
            }

            $vendor->w9_files  = null;
            $vendor->w9_status = 'no';

            $vendor->save();
        }

        elseif ($request->w9_status === 'yes') {

            if($request->hasFile('w9_files')) {

                $vendorId = $vendor->id;

                // 🔢 Get existing form numbers
                $numbers = [];

                foreach ($existingFiles as $file) {
                    if (preg_match('/vendor' . $vendorId . '_w9_form(\d+)\./', $file, $match)) {
                        $numbers[] = (int) $match[1];
                    }
                }

                $counter = empty($numbers) ? 1 : max($numbers) + 1;

                foreach ($request->file('w9_files') as $file) {
                    $extension = $file->getClientOriginalExtension();

                    $filename = 'vendor' . $vendorId . '_w9_form' . $counter . '.' . $extension;

                    $file->storeAs('vendor_w9', $filename, 'public');

                    $existingFiles[] = $filename;
                    $counter++;
                }

                $vendor->update([
                    'w9_files' => json_encode(array_values($existingFiles))
                ]);
            }
            else {
                $validated['w9_files'] = json_encode($existingFiles);
            }
        }

        $vendor = Vendor::updateOrCreate(
            ['id' => $request->id],
            collect($validated)->except(['w9_files'])->toArray()
        );
    
        if ($vendor) {
            return $isNew
                ? redirect()->route('vendors.index')->with('success', 'Vendor created successfully.')
                : redirect()->route('vendors.show', $vendor->id)->with('success', 'Vendor details updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update vendor details.');
        }
    }

    public function show($id)
    {
        $data['page_title'] = "Vendor Details";
        $data['title']      = "Vendors";
        $data['vendor']     = Vendor::findOrFail($id);
        return view('member::vendors.view',$data);
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        if (!empty($vendor->logo) && Storage::disk('public')->exists('vendor_logos/' . $vendor->logo)) {
            Storage::disk('public')->delete('vendor_logos/' . $vendor->logo);
        }
        if (!empty($vendor->w9_files)) {
            $files = json_decode($vendor->w9_files, true);

            if (is_array($files)) {
                foreach ($files as $file) {
                    if (Storage::disk('public')->exists('vendor_w9/' . $file)) {
                        Storage::disk('public')->delete('vendor_w9/' . $file);
                    }
                }
            }
        }
        Storage::delete('public/vendor_logos/' . $vendor->logo);
        $vendor->delete();
        return redirect()->route('vendors.index')->with('success', 'Record deleted successfully');
    }
}
