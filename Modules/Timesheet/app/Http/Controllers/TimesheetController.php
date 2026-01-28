<?php

namespace Modules\Timesheet\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Project\app\Models\Project;
use Modules\Task\app\Models\Task;
use Modules\Timesheet\app\Models\Activity;
use Modules\Timesheet\app\Models\ActivityCustom;

class TimesheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employeeId = Auth::id();

        $activities = Activity::where('employee_id', $employeeId)
            ->orderBy('date', 'desc')
            ->get();

        $timesheets = $activities->groupBy(fn($item) => $item->date->format('Y-m-d'))
            ->map(function ($items, $date) use ($employeeId) {
                $status = 'Approved';
                if ($items->contains(fn($i) => $i->is_approved == 2)) {
                    $status = 'Rejected';
                } elseif ($items->contains(fn($i) => $i->is_approved == 0)) {
                    $status = 'Not Approved';
                }

                return [
                    'date' => $date,
                    'employee_id' => $employeeId,
                    'approval_status' => $status,
                ];
            })->values();

        return view('timesheet::timesheets.index', [
            'timesheets' => $timesheets,
            'page_title' => 'Timesheet List',
            'title'      => 'Timesheets',
        ]);
    }

    public function createOrEdit(Request $request)
    {
        $date = $request->date;
        $employeeId = $request->employee_id ?? Auth::id();

        // Fetch all activities of this employee for the selected date
        $activities = Activity::with(['activityCustoms.task', 'project'])
            ->where('employee_id', $employeeId)
            ->where('date', $date)
            ->get();

        // Prepare activityCustoms grouped by project
        $activityByProject = [];
        foreach ($activities as $activity) {
            foreach ($activity->activityCustoms as $ac) {
                $activityByProject[$activity->project_id][] = [
                    'task_id'    => $ac->task_id,
                    'time_hours' => $ac->time_hours,
                    'task_name'  => $ac->task?->title,
                    'activity_custom_id'=> $ac->id,
                ];
            }
        }
        
        return view('timesheet::timesheets.create', [
            'page_title' => "Edit Timesheet",
            'title'      => "Timesheets",
            'projects'   => Project::all(),
            'tasks'      => Task::all(),
            'activityByProject' => $activityByProject,
            'date'       => $date,
            'employee_id'=> $employeeId,
        ]);
    }

    public function storeOrUpdate(Request $request)
    {

        $rules = [
            'date'        => 'required|date_format:d/m/Y',
            'project_id'  => 'required|array',
            'task_id'     => 'required|array',
            'time_hours'  => 'required|array',
            'project_id.*'=> 'required|exists:projects,id',
            'task_id.*'   => 'required|exists:tasks,id',
            'time_hours.*'=> 'required|numeric|min:0.1',
            'activity_id.*' => 'nullable|exists:activity_customs,id',
        ];

        $validated = $request->validate($rules);

        $employeeId = Auth::id();
        $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

        DB::transaction(function () use ($request, $employeeId, $date) {

            // Group tasks by project
            $grouped = [];
            foreach ($request->project_id as $index => $projectId) {
                $grouped[$projectId][] = [
                    'task_id'           => $request->task_id[$index],
                    'time_hours'        => $request->time_hours[$index],
                    'activity_custom_id'=> $request->activity_id[$index] ?? null,
                ];
            }

            foreach ($grouped as $projectId => $tasks) {

                // Get or create Activity for this project
                $activity = Activity::firstOrCreate(
                    [
                        'employee_id' => $employeeId,
                        'date'        => $date,
                        'project_id'  => $projectId,
                    ],
                    [] // no extra fields for now
                );

                // Keep track of activity_custom_ids to detect deleted tasks
                $existingIds = $activity->activityCustoms()->pluck('id')->toArray();
                $submittedIds = [];

                foreach ($tasks as $task) {
                    if ($task['activity_custom_id']) {
                        // Update existing task
                        $activityCustom = ActivityCustom::find($task['activity_custom_id']);
                        if ($activityCustom) {
                            $activityCustom->update([
                                'task_id'    => $task['task_id'],
                                'time_hours' => $task['time_hours'],
                            ]);
                            $submittedIds[] = $activityCustom->id;
                        }
                    } else {
                        // Insert new task
                        $newTask = ActivityCustom::create([
                            'activity_id' => $activity->id,
                            'task_id'     => $task['task_id'],
                            'time_hours'  => $task['time_hours'],
                        ]);
                        $submittedIds[] = $newTask->id;
                    }
                }

                // Delete removed tasks (optional)
                $toDelete = array_diff($existingIds, $submittedIds);
                if (!empty($toDelete)) {
                    ActivityCustom::whereIn('id', $toDelete)->delete();
                }
            }
        });

        $message = $request->has('id') ? 'Timesheet updated successfully.' : 'Timesheet created successfully.';
        return redirect()->route('timesheets.index')->with('success', $message);
    }

    public function show(Request $request)
    {
        $date = $request->date;
        $employeeId = $request->employee_id ?? Auth::id();

        // Fetch all activities of this employee for the selected date
        $activities = Activity::with(['activityCustoms.task', 'project', 'employee'])
            ->where('employee_id', $employeeId)
            ->where('date', $date)
            ->get();

        // Prepare activityCustoms grouped by project
        $activityByProject = [];
        foreach ($activities as $activity) {
            foreach ($activity->activityCustoms as $ac) {
                $activityByProject[$activity->project_id]['project'] = $activity->project;
                $activityByProject[$activity->project_id]['tasks'][] = [
                    'task_name'  => $ac->task?->title,
                    'time_hours'=> $ac->time_hours,
                ];
            }
        }
        return view('timesheet::timesheets.view', [
            'page_title' => "Timesheet Details",
            'title'      => "Timesheets",
            'activityByProject' => $activityByProject,
            'date'       => $date,
            'employee_id'=> $employeeId,
            'timesheet'  => $activities->first(), // for date & employee
        ]);
    }

    public function destroy($id)
    {
        $timesheet = Activity::findOrFail($id);
        $timesheet->delete();
        return redirect()->route('timesheets.index')->with('success', 'Record deleted successfully');
    }
}
