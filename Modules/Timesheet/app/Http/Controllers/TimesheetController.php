<?php

namespace Modules\Timesheet\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Member\app\Models\Employee;
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
        $user = auth('employee')->user();   // IMPORTANT: use employee guard
        $userId = $user->id;

        // 1) Decide which employee_ids are visible for this user
        $visibleEmployeeIds = [$userId];

        if ($user->designation === 'Project Manager') {

            // own + employees reporting to this PM
            $subIds = Employee::where('reporting_to', $userId)
                ->where('designation', 'Employee')
                ->pluck('id')
                ->toArray();

            $visibleEmployeeIds = array_values(array_unique(array_merge($visibleEmployeeIds, $subIds)));
        }

        elseif ($user->designation === 'PMO') {

            // own + PMs reporting to this PMO
            $pmIds = Employee::where('reporting_to', $userId)
                ->where('designation', 'Project Manager')
                ->pluck('id')
                ->toArray();

            $visibleEmployeeIds = array_values(array_unique(array_merge($visibleEmployeeIds, $pmIds)));
        }

        elseif ($user->designation === 'Admin') {

            // Admin can see everything (recommended)
            $visibleEmployeeIds = Employee::pluck('id')->toArray();

            // If you want ONLY Admin + PMO + PM (not employees), use this instead:
            // $visibleEmployeeIds = Employee::whereIn('designation', ['Admin','PMO','Project Manager'])
            //     ->pluck('id')->toArray();
        }

        // 2) Fetch activities for those employee_ids
        $activities = Activity::whereIn('employee_id', $visibleEmployeeIds)
            ->orderBy('date', 'desc')
            ->get();

        // 3) Group by date + employee_id (important when you list multiple employees)
        $timesheets = $activities
            ->groupBy(fn($item) => $item->date->format('Y-m-d') . '|' . $item->employee_id)
            ->map(function ($items) {

                $status = 'Approved';
                if ($items->contains(fn($i) => $i->is_approved == 2)) {
                    $status = 'Rejected';
                } elseif ($items->contains(fn($i) => $i->is_approved == 0)) {
                    $status = 'Not Approved';
                }

                $first = $items->first();

                return [
                    'date'            => $first->date->format('Y-m-d'),
                    'employee_id'     => $first->employee_id,
                    'approval_status' => $status,
                ];
            })
            ->values();

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

        $projectIds = array_values(array_unique($request->project_id ?? []));

        // collect submitted activity_custom ids (only available in edit)
        $submittedCustomIds = collect($request->activity_id ?? [])
            ->filter()
            ->unique()
            ->values();

        // ✅ figure out which Activity IDs belong to the entry being edited (so we can exclude them)
        $excludeActivityIds = [];
        if ($submittedCustomIds->isNotEmpty()) {
            $excludeActivityIds = ActivityCustom::whereIn('id', $submittedCustomIds)
                ->pluck('activity_id')
                ->unique()
                ->values()
                ->all();
        }

        // ✅ DUPLICATE CHECK (works for both CREATE + UPDATE date change)
        $duplicateExists = Activity::where('employee_id', $employeeId)
            ->where('date', $date)
            ->whereIn('project_id', $projectIds)
            ->when(!empty($excludeActivityIds), function ($q) use ($excludeActivityIds) {
                // during update, ignore the current activities being updated
                $q->whereNotIn('id', $excludeActivityIds);
            })
            ->exists();

        if ($duplicateExists) {
            throw ValidationException::withMessages([
                'project_id' => ['Repetition error: This project is already added for the selected date.'],
            ]);
        }

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

                // ✅ If editing: find the Activity from existing activity_custom_ids
                $existingCustomIds = collect($tasks)
                    ->pluck('activity_custom_id')
                    ->filter()
                    ->values();

                if ($existingCustomIds->isNotEmpty()) {
                    // Find the existing activity through any existing activity_custom row
                    $activityId = ActivityCustom::whereIn('id', $existingCustomIds)
                        ->value('activity_id');

                    $activity = Activity::findOrFail($activityId);

                    // ✅ Update the SAME Activity (change date/project if user changed)
                    $activity->update([
                        'date'       => $date,
                        'project_id' => $projectId,
                    ]);
                } else {
                    // ✅ Create mode (new project/tasks)
                    $activity = Activity::create(
                        [
                            'employee_id' => $employeeId,
                            'date'        => $date,
                            'project_id'  => $projectId,
                        ],
                        [] // no extra fields for now
                    );
                }

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

    public function approve(Request $request)
    {
        $user = auth('employee')->user();

        if ($user->designation === 'Employee') {
            abort(403, 'Unauthorized action.');
        }

        $date       = $request->date;
        $employeeId = $request->employee_id;

        Activity::where('employee_id', $employeeId)
            ->where('date', $date)
            ->update([
                'is_approved' => 1,
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);

        return back()->with('success', 'Timesheet approved successfully.');
    }

    public function destroy(Request $request)
    {
        $employee   = auth('employee')->user();
        $empDesig   = $employee->designation;
        $date       = $request->date;
        $employeeId = $request->employee_id;

        $query = Activity::where('date', $date)
            ->where('employee_id', $employeeId);

        // Employee can delete only own records
        if ($empDesig === 'Employee') {
            $query->where('employee_id', $employee->id);
        }

        $timesheets = $query->get();

        if ($timesheets->isEmpty()) {
            abort(403, 'Unauthorized or invalid record.');
        }

        foreach ($timesheets as $timesheet) {
            $timesheet->delete();
        }

        return redirect()
            ->route('timesheets.index')
            ->with('success', 'Record deleted successfully');
    }
}
