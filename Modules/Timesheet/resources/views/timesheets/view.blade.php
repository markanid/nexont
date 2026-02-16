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
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('timesheets.index') }}">{{ $title }}</a></li>
                    <li class="breadcrumb-item active">{{ $page_title }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@section('body')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-tasks"></i> {{ $page_title }}</h3>
        <div class="card-tools">
            @php
                $loggedUser = auth('employee')->user();
            @endphp

            @if($loggedUser->designation !== 'Employee' && $timesheet->is_approved != 1)
                <form action="{{ route('timesheets.approve') }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="employee_id" value="{{ $employee_id }}">
                    <button type="submit" class="btn btn-warning btn-sm btn-flat">
                        <i class="fas fa-check"></i> Approve
                    </button>
                </form>
            @endif
            <a href="{{ route('timesheets.edit',['date' => $date, 'employee_id' => $employee_id]) }}" class="btn btn-success btn-sm btn-flat"> <i class="fas fa-edit"></i> Edit </a>
            <a href="{{ route('timesheets.index') }}" class="btn btn-dark btn-sm btn-flat"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
        </div>
    </div>
</div>

<div class="card card-navy">
    <div class="card-header">
        <h3 class="card-title"><i class="far fa-file-alt"></i> Basic Details</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>Date</td>
                        <td style="color: #007bff;">{{ \Carbon\Carbon::parse($timesheet->date)->format('d-M-Y') }}</td>
                    </tr>
                    <tr>
                        <td>Employee</td>
                        <td> {{ $timesheet->employee?->name }} </td>
                    </tr>
                    <tr>
                        <td>Approval</td>
                        <td>
                            @if($timesheet->is_approved == 1)
                                <span class="badge badge-success">Approved</span>
                            @elseif($timesheet->is_approved == 2)
                                <span class="badge badge-danger">Rejected</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div id="projectTables">
                @foreach($activityByProject as $projectData)
                    <div class="card mt-3">
                        <div class="card-header bg-black">
                            <strong>{{ $projectData['project']->project_name }}</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Task</th>
                                        <th width="200">Time (Hours)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projectData['tasks'] as $task)
                                        <tr>
                                            <td>{{ $task['task_name'] }}</td>
                                            <td>{{ $task['time_hours'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
