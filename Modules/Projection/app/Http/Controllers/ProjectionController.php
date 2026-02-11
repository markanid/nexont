<?php

namespace Modules\Projection\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        return view('projection::projections.index', [
            'projections' => Projection::latest()->get(),
            'page_title'  => 'Projection List',
            'title'       => 'Projections',
            'empDesig'    => auth('employee')->user()->designation ?? '',
        ]); 
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
        $employee = auth('employee')->user();
        $empDesig = $employee->designation;

        $running_projects = collect(); // default empty

        if ($empDesig === 'Project Manager') {
            $running_projects = RunningProject::with('project.client')
                ->where('projection_id', $id)
                ->where('created_by', $employee->id)
                ->get();
        }

        // Admin / PMO → keep empty initially
        return view('projection::projections.view', [
            'empDesig'            => $empDesig,
            'title'               => 'Projections',
            'page_title'          => 'Projection Details',
            'projection'          => Projection::findOrFail($id),
            'running_projects'    => $running_projects,
            'projection_summary'  => in_array($empDesig, ['Admin', 'PMO'])
                ? RunningProject::select(
                    'created_by',
                    DB::raw('SUM(projection_value) as total_projection')
                )
                ->where('projection_id', $id)
                ->groupBy('created_by')
                ->with('creator')
                ->get()
                : collect(),
        ]);

        // $runningQuery = RunningProject::with('project.client')
        //     ->where('projection_id', $id);

        // // If Project Manager → only own data
        // if ($empDesig === 'Project Manager') {
        //     $runningQuery->where('created_by', $employee->id);
        // }

        // return view('projection::projections.view', [
        //     'empDesig'            => $empDesig,
        //     'title'               => 'Projections',
        //     'page_title'          => 'Projection Details',
        //     'projection'          => Projection::findOrFail($id),
        //     'running_projects'    => $runningQuery->get(),
        //     'projection_summary'  => in_array($empDesig, ['Admin', 'PMO'])
        //         ? RunningProject::select(
        //             'created_by',
        //             DB::raw('SUM(projection_value) as total_projection')
        //         )
        //         ->where('projection_id', $id)
        //         ->groupBy('created_by')
        //         ->with('creator')
        //         ->get()
        //         : collect(), // empty for PM
        // ]);
    }

    public function destroy($id)
    {
        $projection = Projection::findOrFail($id);
        $projection->delete();
        return redirect()->route('projections.index')->with('success', 'Record deleted successfully');
    }

    public function filterRunningProjects(Request $request, $projectionId)
    {
        $query = RunningProject::with(['project.client'])
            ->where('projection_id', $projectionId);

        if ($request->created_by) {
            $query->where('created_by', $request->created_by);
        }

        return response()->json($query->get());
    }
}
