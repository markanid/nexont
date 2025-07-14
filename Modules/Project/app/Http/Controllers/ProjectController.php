<?php

namespace Modules\Project\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Master\app\Models\Company;
use Modules\Master\app\Models\User;
use Modules\Project\app\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $data['projects']   = Project::latest('id')->get();
        $data['page_title'] = "Project List";
        $data['title']      = "Projects";
        return view('project::projects.index', $data);  
    }

    public function createOrEdit($id = null)
    {
        $project            = $id ? Project::findOrFail($id) : new Project();
        $data['page_title'] = $id ? "Edit Project" : "Create Project";
        $data['title']      = "Projects";
        $data['project']    = $project;
        $data['project_id'] = $id ? $project->project_id : Project::getProjectID();
        $data['companies']  = Company::all();
        $data['clients']    = User::where('role', 'Client')->get();
        $data['users']      = User::where('role', '!=', 'Client')->get();
        return view('project::projects.create', $data);
    }

    public function storeOrUpdate(Request $request)
    {
        $request->merge([
            'start_date' => $request->start_date ? Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d') : null,
            'end_date'  => $request->end_date ? Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d') : null,
        ]);

        $rules = [
            'project_id'        => 'required|string|max:255|unique:projects,project_id,' . $request->id,
            'project_name'      => 'required|string|max:255',
            'company_id'        => 'required|exists:companies,id',
            'client_id'         => 'required|exists:users,id',
            'project_manager_id'=> 'nullable|exists:users,id',
            'sales_manager_id'  => 'nullable|exists:users,id',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'project_cost'      => 'nullable|numeric|min:0',
            'status'            => 'required|in:Pending,Active,Completed,On Hold',
        ];
        $validated = $request->validate($rules);
        $isNew = empty($request->id);

        $project = Project::updateOrCreate(
            ['id' => $request->id ?? null],
            $validated
        );
    
        if ($project) {
            return $isNew
                ? redirect()->route('projects.index')->with('success', 'Project  created successfully.')
                : redirect()->route('projects.show', $project->id)->with('success', 'Project details updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update project details.');
        }
    }

    public function show($id)
    {
        $data['title']      = "Projects";
        $data['page_title'] = "Project Details";
        $data['project']   = Project::findOrFail($id);
        return view('project::projects.view', $data);
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Record deleted successfully');
    }
}
