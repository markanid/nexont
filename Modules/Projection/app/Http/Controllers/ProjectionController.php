<?php

namespace Modules\Projection\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Project\app\Models\Project;
use Modules\Projection\app\Models\Projection;
use Modules\Projection\app\Models\RunningProject;

class ProjectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['projections']= Projection::latest('id')->get();
        $data['page_title'] = "Projection List";
        $data['title']      = "Projections";
        return view('projection::projections.index', $data);  
    }

    public function createOrEdit($id = null)
    {
        $projection         = $id ? Projection::findOrFail($id) : new Projection();
        $data['page_title'] = $id ? "Edit Projection" : "Create Projection";
        $data['title']      = "Projections";
        $data['projection'] = $projection;
        return view('projection::projections.create', $data);
    }

    public function storeOrUpdate(Request $request)
    {

        $rules = [
            'month'     => 'required|string',
            'year'      => 'required|string',
            'created_by'=> 'required|integer|exists:users,id',
        ];
        $validated = $request->validate($rules);
        $isNew = empty($request->id);

        $projection = Projection::updateOrCreate(
            ['id' => $request->id ?? null],
            $validated
        );
    
        if ($projection) {
            return $isNew
                ? redirect()->route('projections.index')->with('success', 'Projection  created successfully.')
                : redirect()->route('projections.show', $projection->id)->with('success', 'Projection details updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update project details.');
        }
    }
    
    public function show($id)
    {
        $data['title']              = "Projections";
        $data['page_title']         = "Projection Details";
        $data['projection']         = Projection::findOrFail($id);
        $data['running_projects']   = RunningProject::with(['project.client'])->where('projection_id', $id)->get();
        return view('projection::projections.view', $data);
    }

    public function destroy($id)
    {
        $projection = Projection::findOrFail($id);
        $projection->delete();
        return redirect()->route('projections.index')->with('success', 'Record deleted successfully');
    }
}
