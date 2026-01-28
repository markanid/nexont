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
                    <a class="btn btn-primary btn-sm btn-flat float-right" href="{{route('timesheets.create')}}"><i class="fas fa-plus-circle"></i> Create</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body table-responsive">
                    <table id="timesheets_table" class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>SNo</th>
                                <th>Date</th>
                                <th>Approval Status</th>
                                <th>Options</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($timesheets as $timesheet)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a href="{{route('timesheets.show', ['date' => $timesheet['date'], 'employee_id' => $timesheet['employee_id']])}}">{{ \Carbon\Carbon::parse($timesheet['date'])->format('d-M-Y') }}</a></td>
                                    <td>{{ $timesheet['approval_status'] }}</td>
                                    <td>
                                        @if($timesheet['approval_status'] === 'Not Approved')
                                            <a class="btn btn-app" href="{{ route('timesheets.edit', ['date' => $timesheet['date'], 'employee_id' => $timesheet['employee_id']]) }}"><i class="far fa-edit"></i></a>
                                            <a href="#" class="btn btn-app-delete delete-btn" data-url="{{ route('timesheets.delete', ['date' => $timesheet['date'], 'employee_id' => $timesheet['employee_id']]) }}"><i class="far fa-trash-alt"></i></a>
                                        @endif
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
@include('partials.common-index-script', ['tableId' => 'timesheets_table'])
@endsection
