<?php

namespace Modules\Task\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Task\app\Models\Task;

class TaskController extends Controller
{
    
    public function index()
    {
        $data['tasks']      = Task::latest('id')->get();
        $data['page_title'] = "Task List";
        $data['title']      = "Tasks";
        return view('task::tasks.index', $data);  
    }
    
    public function createOrEdit($id = null)
    {
        $data['page_title'] = $id ? "Edit Task" : "Create Task";
        $data['title']      = "Tasks";
        $data['task']       = $id ? Task::findOrFail($id) : new Task();
        return view('task::tasks.create', $data);
    }

    public function storeOrUpdate(Request $request)
    {

        $rules = [
            'title'             => 'required|string',
            'category_code'     => 'required|string',
            'activity_code'     => 'required|string',
            'work_descipline'   => 'required|string',
            'production_type'   => 'required|string',
            'production_stage'  => 'required|string',
        ];
        $validated = $request->validate($rules);
        $isNew = empty($request->id);

        $task = Task::updateOrCreate(
            ['id' => $request->id ?? null],
            $validated
        );

        if ($task) {
            return $isNew
                ? redirect()->route('tasks.index')->with('success', 'Task created successfully.')
                : redirect()->route('tasks.show', $task->id)->with('success', 'Task details updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update task details.');
        }
    }

    public function show($id)
    {
        $data['title']      = "Tasks";
        $data['page_title'] = "Task Details";
        $data['task']       = Task::findOrFail($id);
        return view('task::tasks.view', $data);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Record deleted successfully');
    }
}
