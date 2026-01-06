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

    public function adddetails($id = null)
    {
        $data['page_title']     = $id ? "Create Running Project" : "Create Running Project";
        $data['title']          = "Running Project";
        $data['projects']       = Project::all();
        $data['projection_id']  = $id;
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
            return $isNew
                ? redirect()->route('projections.show', $projectiondetail->projection_id)->with('success', 'Running project created successfully.')
                : redirect()->route('projections.show', $projectiondetail->projection_id)->with('success', 'Running project updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update running project.');
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('projection::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('projection::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
