<?php

namespace Modules\Projection\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Project\app\Models\Project;
use Modules\Projection\app\Models\RunningProject;

class RunningProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('projection::index');
    }

    public function addOrEdit($id = null)
    {
        $data['page_title']     = $id ? "Create Running Project" : "Create Running Project";
        $data['title']          = "Running Project";
        $data['projects']       = Project::all();
        if ($id) {
            // EDIT
            $data['runningProject'] = RunningProject::findOrFail($id);
            $data['projection_id']  = $data['runningProject']->projection_id;
        } else {
            // CREATE
            $data['runningProject'] = null;
            $data['projection_id']  = request()->get('projection_id');
        }
    
        return view('projection::runningprojects.create',$data);
    }

    public function storeOrUpdate(Request $request)
    {

        $rules = [
            'projection_id'     => 'required|integer|exists:projections,id',
            'project_id'        => 'required|integer|exists:projects,id',
            'projection_value'  => 'required|numeric',
            'type'              => 'required|string',
            'billing_desc'      => 'nullable|string',
            'status'            => 'required|string',
            'remarks'           => 'nullable|string',
            'created_by'        => 'required|integer|exists:users,id',
        ];

        $validated = $request->validate($rules);
        $isNew = empty($request->id);

        $projectiondetail = RunningProject::updateOrCreate(
            ['id' => $request->id],
            $validated
        );
        
        if ($projectiondetail) {
            return redirect()
                ->route('projections.show', $projectiondetail->projection_id)
                ->with('success', $isNew
                    ? 'Running project created successfully.'
                    : 'Running project updated successfully.'
                )
                ->withFragment('custom-tabs-one-profile');
        } else {
            return redirect()->back()->with('error', 'Failed to update running project.');
        }
    }

    public function delete($id)
    {
        $runningProject = RunningProject::findOrFail($id);
        $runningProject->delete();
        return redirect()->route('projections.show', $runningProject->projection_id)->with('success', 'Record deleted successfully');
    }
}
