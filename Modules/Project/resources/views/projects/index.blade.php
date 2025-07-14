@extends('components.layout')

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"></h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">{{$title}}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection
<!-- /.content-header -->
@section('body')       
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-tag"></i> {{$page_title}}</h3>
                    <a class="btn btn-primary btn-sm btn-flat float-right" href="{{route('projects.create')}}"><i class="fas fa-plus-circle"></i> Create</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body table-responsive">
                    <table id="projects_table" class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>SNo</th>
                                <th>Project ID</th>
                                <th>Project Name</th>
                                <th>Company</th>
                                <th>Client</th>
                                <th>Options</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{$project->project_id }}</td>
                                    <td><a href="{{route('projects.show', $project->id)}}">{{$project->project_name}}</a></td>
                                    <td><a href="{{route('companies.show', $project->company_id)}}">{{$project->company->name}}</a></td>
                                    <td><a href="{{route('users.show', $project->client_id)}}">{{$project->client->name}}</a></td>
                                    <td>
                                    <a class="btn btn-app" href="{{route('projects.edit', $project->id)}}"><i class="far fa-edit"></i></a>
                                    <a href="#" class="btn btn-app-delete delete-btn" data-url="{{ route('projects.delete', ['id' => $project->id]) }}"><i class="far fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.delete-modal')

@section('scripts')

@include('partials.delete-modal-script')
@include('partials.common-index-script', ['tableId' => 'projects_table'])
@endsection
