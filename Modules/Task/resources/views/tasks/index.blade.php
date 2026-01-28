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
                    <h3 class="card-title"><i class="fas fa-tasks"></i> {{$title}}</h3>
                    <a class="btn btn-primary btn-sm btn-flat float-right" href="{{route('tasks.create')}}"><i class="fas fa-plus-circle"></i> Create</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body table-responsive">
                    <table id="tasks_table" class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>SNo</th>
                                <th>Name</th>
                                <th>Category Code</th>
                                <th>Activity Code</th>
                                <th>Work Descipline</th>
                                <th>Production Type</th>
                                <th>Production Stage</th>
                                <th>Options</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a href="{{route('tasks.show', $task->id)}}">{{$task->title }}</a></td>
                                    <td>{{$task->category_code}}</td>
                                    <td>{{$task->activity_code}}</td>
                                    <td>{{$task->work_descipline}}</a></td>
                                    <td>{{$task->production_type}}</td>
                                    <td>{{$task->production_stage}}</td>
                                    <td>
                                    <a class="btn btn-app" href="{{route('tasks.edit', $task->id)}}"><i class="far fa-edit"></i></a>
                                    <a href="#" class="btn btn-app-delete delete-btn" data-url="{{ route('tasks.delete', ['id' => $task->id]) }}"><i class="far fa-trash-alt"></i></a>
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
@include('partials.common-index-script', ['tableId' => 'tasks_table'])
@endsection
