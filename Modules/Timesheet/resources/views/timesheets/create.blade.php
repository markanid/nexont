@extends('components.layout')

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('timesheets.index')}}">{{$title}}</a></li>
                    <li class="breadcrumb-item active">{{$page_title}}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@section('body')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-tasks"></i> {{$page_title}}</h3>
        <a class="btn btn-dark btn-sm btn-flat float-right" href="{{route('timesheets.index')}}"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
        @if ($errors->any())
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    toastr.error(`{!! implode('<br>', $errors->all()) !!}`, 'Validation Error');
                });
            </script>
        @endif
    </div>
</div>
<div class="card card-navy">
    <div class="card-header">
        <h3 class="card-title"><i class="far fa-file-alt"></i> Basic Details</h3>
    </div>
    <form id="ProjectForm" method="post" action="{{ route('timesheets.update') }}" enctype="multipart/form-data">
    @csrf
        <input type="hidden" name="id" value="{{ $timesheet->id ?? '' }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Date <sup>*</sup></label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" name="date" id="date" data-target="#reservationdate" tabindex="1" value="{{ old('date', $date ? \Carbon\Carbon::parse($date)->format('d/m/Y') : '') }}" />
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            @if ($errors->has('date'))
                            <span class="text-danger">{{ $errors->first('date') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Project <sup>*</sup></label>
                        <select name="project" id="project" class="form-control" tabindex="2">
                            <option value="">Select</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">
                                    {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('project'))
                          <span class="text-danger">{{ $errors->first('project') }}</span>
                        @endif
                    </div>
                </div>
                {{-- <div class="col-md-4">
                    <div class="form-group">
                        <label>Tasks <sup>*</sup></label>
                        <div class="border p-2" style="max-height:200px; overflow-y:auto;">
                            @foreach($tasks as $task)
                                <div class="form-check">
                                    <input class="form-check-input task-checkbox" 
                                        type="checkbox" 
                                        value="{{ $task->id }}" 
                                        data-name="{{ $task->title }}" 
                                        id="task_{{ $task->id }}">
                                    <label class="form-check-label" for="task_{{ $task->id }}">
                                        {{ $task->title }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div> --}}

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tasks <sup>*</sup></label>
                        <select id="task_select" multiple class="form-control">
                            @foreach($tasks as $task)
                                <option value="{{ $task->id }}">{{ $task->title }}</option>
                            @endforeach
                        </select>
                        {{-- <select id="task_select" class="form-control" tabindex="3">
                            <option value="">Select</option>
                            @foreach($tasks as $task)
                                <option value="{{ $task->id }}" data-name="{{ $task->title }}">
                                    {{ $task->title }}
                                </option>
                            @endforeach
                        </select> --}}
                    </div>
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="button" id="addTaskBtn" class="btn btn-success btn-block">
                        Add Task
                    </button>
                </div>
            </div>
            
            <div id="projectTables" class="mt-3"></div>
        </div>
        <div class="card-footer" align="center">
            <button type="submit" id="submitBtn" class="btn btn-primary btn-flat" tabindex="4"><i class="fas fa-save"></i> Save</button>
             <button type="reset" value="Reset" id="resetbtn" class="btn btn-secondary  btn-flat" tabindex="8"><i class="fas fa-undo-alt"></i> Reset</button>
        </div>
    </form>
</div>
@endsection
@section('styles')
<style>
.group-box {
    border: 1px solid #c0c1c5;
    border-radius: 8px;
    padding: 18px 15px 10px;
    position: relative;
    background: #343a40;
    margin-top: 12px;
}

.group-box .group-title {
    position: absolute;
    top: -12px;
    left: 15px;
    background: #343a40;
    padding: 0 8px;
    color: #ffffff;
}

#task_select {
    display: none;
}

.select2-container {
    width: 100% !important;
}
</style>
@endsection

@section('scripts')
<script>
$(function () {
    
    $('#task_select').select2({
        placeholder: "Select Tasks",
        closeOnSelect: false,
        width: '100%',
        templateResult: formatState,
        templateSelection: formatState
    });

    function formatState(state) {
        if (!state.id) return state.text;
        return $('<span><input type="checkbox" style="margin-right:8px;" /> ' + state.text + '</span>');
    }

    function syncCheckboxes() {
        setTimeout(() => {
            $('.select2-results__option').each(function () {
                let option = $(this);
                let isSelected = option.attr('aria-selected') === 'true';
                option.find('input[type="checkbox"]').prop('checked', isSelected);
            });
        }, 10);
    }

    // Sync checkbox state on select/unselect
    $('#task_select').on('select2:select select2:unselect', function () {
        syncCheckboxes();
    });

    // ALSO sync when dropdown opens
    $('#task_select').on('select2:open', function () {
        syncCheckboxes();
    });

    bsCustomFileInput.init();
    $.validator.setDefaults({
        submitHandler: function (form) {
            $('#submitBtn').prop('disabled', true); // Disable the submit button
            form.submit();
        }
    });
});

$('#project').change(function () {
    $('#task_select').val('').trigger('change'); // Reset task select
});

// Add Task Button Click
// $('#addTaskBtn').click(function () {
//     let projectId   = $('#project').val();
//     let projectName = $('#project option:selected').text();
//     let taskId      = $('#task_select').val();
//     let taskName    = $('#task_select option:selected').data('name');

//     if (!projectId || !taskId) {
//         toastr.error('Project or task is missing', 'Missing Information');
//         return;
//     }

//     createProjectTable(projectId, projectName);

//     let tableBody = $('#project-table-' + projectId + ' .task-body');

//     if (tableBody.find(`input[name="task_id[]"][value="${taskId}"]`).length) {
//         toastr.error('This task is already added for the selected project!', 'Duplicate Task');
//         return;
//     }

//     // Append new task (no activity_id yet)
//     tableBody.append(`
//         <tr>
//             <td>
//                 ${taskName}
//                 <input type="hidden" name="project_id[]" value="${projectId}">
//                 <input type="hidden" name="task_id[]" value="${taskId}">
//                 <input type="hidden" name="activity_id[]" value="">
//             </td>
//             <td>
//                 <input type="number" step="0.1" class="form-control" name="time_hours[]" required>
//             </td>
//             <td>
//                 <button type="button" class="btn btn-danger btn-sm removeRow">
//                     <i class="fas fa-trash"></i>
//                 </button>
//             </td>
//         </tr>
//     `);

//     $('#task_select').val('').trigger('change');
// });
$('#addTaskBtn').click(function () {
    let projectId   = $('#project').val();
    let projectName = $('#project option:selected').text();

    if (!projectId) {
        toastr.error('Project is missing', 'Missing Information');
        return;
    }

    let taskIds = $('#task_select').val(); // array of selected task IDs

    if (!taskIds || taskIds.length === 0) {
        toastr.error('Please select at least one task', 'Missing Information');
        return;
    }

    createProjectTable(projectId, projectName);
    let tableBody = $('#project-table-' + projectId + ' .task-body');

    taskIds.forEach(function(taskId){
        let taskName = $('#task_select option[value="'+taskId+'"]').text();

        // prevent duplicate task per project
        if (tableBody.find(`input[name="task_id[]"][value="${taskId}"]`).length) {
            return; // skip duplicate
        }

        tableBody.append(`
            <tr>
                <td>
                    ${taskName}
                    <input type="hidden" name="project_id[]" value="${projectId}">
                    <input type="hidden" name="task_id[]" value="${taskId}">
                    <input type="hidden" name="activity_id[]" value="">
                </td>
                <td>
                    <input type="number" step="0.1" class="form-control" name="time_hours[]" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm removeRow">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);
    });

    // Clear selection after adding
    $('#task_select').val(null).trigger('change');
});



// Create Project Table
function createProjectTable(projectId, projectName) {
    if ($('#project-table-' + projectId).length) return;

    $('#projectTables').append(`
        <div class="card mt-3" id="project-table-${projectId}">
            <div class="card-header bg-black">
                <strong>${projectName}</strong>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th width="200">Time (Hours)</th>
                            <th width="80">Action</th>
                        </tr>
                    </thead>
                    <tbody class="task-body"></tbody>
                </table>
            </div>
        </div>
    `);
}

// Remove Task Row
$(document).on('click', '.removeRow', function () {
    let tableCard = $(this).closest('.card');
    $(this).closest('tr').remove();
    if (tableCard.find('tbody tr').length === 0) {
        tableCard.remove();
    }
});
// On page load, populate tasks if editing
$(document).ready(function () {
    let activityData = @json($activityByProject ?? []);

    Object.keys(activityData).forEach(projectId => {
        let projectName = $('#project option[value="' + projectId + '"]').text();
        createProjectTable(projectId, projectName);

        let tableBody = $('#project-table-' + projectId + ' .task-body');

        activityData[projectId].forEach(item => {
            tableBody.append(`
                <tr>
                    <td>
                        ${item.task_name}
                        <input type="hidden" name="project_id[]" value="${projectId}">
                        <input type="hidden" name="task_id[]" value="${item.task_id}">
                        <input type="hidden" name="activity_id[]" value="${item.activity_custom_id}">
                    </td>
                    <td>
                        <input type="number" step="0.1" class="form-control" name="time_hours[]" value="${item.time_hours}" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm removeRow">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    });
});



</script>
@endsection