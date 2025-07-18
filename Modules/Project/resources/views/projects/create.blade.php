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
                    <li class="breadcrumb-item"><a href="{{route('projects.index')}}">{{$title}}</a></li>
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
        <h3 class="card-title"><i class="fas fa-user-tag"></i> {{$page_title}}</h3>
        <a class="btn btn-dark btn-sm btn-flat float-right" href="{{route('projects.index')}}"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
    </div>
</div>
<div class="card card-navy">
    <div class="card-header">
        <h3 class="card-title"><i class="far fa-file-alt"></i> Basic Details</h3>
    </div>
    <form id="ProjectForm" method="post" action="{{ route('projects.update') }}" enctype="multipart/form-data">
    @csrf
        <input type="hidden" name="id" value="{{ $project->id ?? '' }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Project ID <sup>*</sup></label>
                        <input type="text" name="project_id" class="form-control" value="{{ old('project_id', $project->project_id ?? $project_id) }}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Project Name <sup>*</sup></label>
                        <input type="text" name="project_name" class="form-control" value="{{ old('project_name', $project->project_name ?? '') }}" tabindex="1">
                        @if ($errors->has('project_name'))
                          <span class="text-danger">{{ $errors->first('project_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Company <sup>*</sup></label>
                        <select name="company_id" class="form-control" tabindex="2">
                            <option value="">-- Select Company --</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id', $project->company_id ?? '') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('company_id'))
                          <span class="text-danger">{{ $errors->first('company_id') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Client <sup>*</sup></label>
                        <select name="client_id" class="form-control" tabindex="3">
                            <option value="">-- Select Client --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $project->client_id ?? '') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('client_id'))
                        <span class="text-danger">{{ $errors->first('client_id') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Project Manager <sup>*</sup></label>
                        <select name="project_manager_id" class="form-control" tabindex="4">
                            <option value="">-- Select Project-Manager --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('project_manager_id', $project->project_manager_id ?? '') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('project_manager_id'))
                          <span class="text-danger">{{ $errors->first('project_manager_id') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Sales Manager <sup>*</sup></label>
                        <select name="sales_manager_id" class="form-control" tabindex="5">
                            <option value="">-- Select Sales-Manager --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('sales_manager_id', $project->sales_manager_id ?? '') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('sales_manager_id'))
                          <span class="text-danger">{{ $errors->first('sales_manager_id') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Start Date</label>
                        <div class="input-group date" id="start_date" data-target-input="nearest">
                            <input type="text" name="start_date" class="form-control" value="{{ old('start_date', isset($project->start_date) ? \Carbon\Carbon::parse ($project->start_date)->format('d/m/Y') : '') }}" tabindex="6">
                            <div class="input-group-append" data-target="#start_date" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            @if ($errors->has('start_date'))
                            <span class="text-danger">{{ $errors->first('start_date') }}</span>
                            @endif
                        </div>  
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>End Date</label>
                        <div class="input-group date" id="end_date" data-target-input="nearest">
                            <input type="text" name="end_date" class="form-control" value="{{ old('end_date', isset($project->end_date) ? \Carbon\Carbon::parse ($project->end_date)->format('d/m/Y') : '') }}" tabindex="7">
                            <div class="input-group-append" data-target="#end_date" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            @if ($errors->has('end_date'))
                            <span class="text-danger">{{ $errors->first('end_date') }}</span>
                            @endif
                        </div>  
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Project Cost</label>
                        <input type="text" name="project_cost" class="form-control" value="{{ old('project_cost', $project->project_cost ?? '0.00') }}" tabindex="8">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" tabindex="9">
                             @foreach(['Planned', 'On Going', 'Completed', 'On Hold', 'Cancelled'] as $status)
                                <option value="{{ $status }}" {{ old('status', $project->status ?? '') == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer" align="center">
            <button type="submit" id="submitBtn" class="btn btn-primary btn-flat" tabindex="10"><i class="fas fa-save"></i> Save</button>
             <button type="reset" value="Reset" id="resetbtn" class="btn btn-secondary  btn-flat" tabindex="11"><i class="fas fa-undo-alt"></i> Reset</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    bsCustomFileInput.init();
    $('#customFile').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass("selected").html(fileName);
        
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#photo_preview').html('<img src="' + e.target.result + '" alt="Customer Photo" style="width: 150px; height: 150px;">');
            }
            reader.readAsDataURL(file);
        }
    });
    $.validator.setDefaults({
        submitHandler: function (form) {
            $('#submitBtn').prop('disabled', true); // Disable the submit button
            form.submit();
        }
    });
    $('#start_date').datetimepicker({
        format: 'DD/MM/YYYY'
    });
    $('#end_date').datetimepicker({
        format: 'DD/MM/YYYY'
    });
});
</script>
@endsection