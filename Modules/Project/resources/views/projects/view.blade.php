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
                    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">{{ $title }}</a></li>
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
            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-success btn-sm btn-flat"><i class="fas fa-edit"></i> Edit</a>
            <a href="{{ route('projects.index') }}" class="btn btn-dark btn-sm btn-flat"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
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
                        <td>Project ID</td>
                        <td style="color: #007bff;">{{ $project->project_id }}</td>
                    </tr>
                    <tr>
                        <td>Project Name</td>
                        <td>{{ $project->project_name }}</td>
                    </tr>
                    <tr>
                        <td>Company</td>
                        <td>{{ $project->company->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Client</td>
                        <td>{{ $project->client->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Project Manager</td>
                        <td>{{ $project->projectManager->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Sales Manager</td>
                        <td>{{ $project->salesManager->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Start Date</td>
                        <td>{{ $project->start_date ? $project->start_date->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td>End Date</td>
                        <td>{{ $project->end_date ? $project->end_date->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Project Cost</td>
                        <td>QAR {{ number_format($project->project_cost, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>{{ $project->status }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
