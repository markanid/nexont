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
                    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employee</a></li>
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
        <h3 class="card-title"><i class="fas fa-user-tag"></i> {{ $page_title }}</h3>
        <div class="card-tools">
            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-success btn-sm btn-flat"><i class="fas fa-edit"></i> Edit</a>
            <a href="{{ route('employees.index') }}" class="btn btn-dark btn-sm btn-flat"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
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
                        <td style="width: 126px; height: 203px;">Employee Image</td>
                        <td>
                            @if(!empty($employee->image))
                                <p><img src="{{ asset('storage/employee_logos/'.$employee->image) }}" alt="Employee Photo" style="width: 150px; height: 150px;"></p>
                            @else
                                <p><img src="{{ asset('uploads/avatar.png') }}" alt="Employee Photo" style="width: 150px; height: 150px;"></p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 69px;">Employee Code</td>
                        <td style="color: #007bff;">{{ $employee->employee_code }}</td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>{{ $employee->name }}</td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td>{{ $employee->phone }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $employee->email }}</td>
                    </tr>
                    <tr>
                        <td>Designation</td>
                        <td>{{ $employee->designation }}</td>
                    </tr>
                    <tr>
                        <td>Target</td>
                        <td>{{ $employee->target }}</td>
                    </tr>
                    <tr>
                        <td>Reporting To</td>
                        <td>{{ $employee->reportingTo->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>{{ $employee->status }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
