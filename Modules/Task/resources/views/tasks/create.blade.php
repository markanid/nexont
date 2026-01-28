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
                    <li class="breadcrumb-item"><a href="{{route('tasks.index')}}">{{$title}}</a></li>
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
        <a class="btn btn-dark btn-sm btn-flat float-right" href="{{route('tasks.index')}}"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
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
    <form id="ProjectForm" method="post" action="{{ route('tasks.update') }}" enctype="multipart/form-data">
    @csrf
        <input type="hidden" name="id" value="{{ $task->id ?? '' }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Name <sup>*</sup></label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $task->title ?? '') }}" tabindex="1">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Category Code <sup>*</sup></label>
                        <input type="text" name="category_code" id="category_code" class="form-control" value="{{ old('category_code', $task->category_code ?? '') }}" tabindex="2">
                        @if ($errors->has('category_code'))
                          <span class="text-danger">{{ $errors->first('category_code') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Activity Code <sup>*</sup></label>
                        <input type="text" name="activity_code" id="activity_code" class="form-control" value="{{ old('activity_code', $task->activity_code ?? '') }}" tabindex="3">
                        @if ($errors->has('activity_code'))
                          <span class="text-danger">{{ $errors->first('activity_code') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Work Descipline <sup>*</sup></label>
                        <select name="work_descipline" id="work_descipline" class="form-control" tabindex="4">
                            <option value="">-- Select Work Descipline --</option>
                            <option value="Technical_Modeling" {{ old('work_descipline', $task->work_descipline ?? '') == 'Technical_Modeling' ? 'selected' : '' }}>Technical Modeling</option>
                            <option value="Technical_Estimation" {{ old('work_descipline', $task->work_descipline ?? '') == 'Technical_Estimation' ? 'selected' : '' }}>Technical Estimation</option>
                            <option value="Technical_Erection" {{ old('work_descipline', $task->work_descipline ?? '') == 'Technical_Erection' ? 'selected' : '' }}>Technical Erection</option>
                            <option value="Technical_Detailing" {{ old('work_descipline', $task->work_descipline ?? '') == 'Technical_Detailing' ? 'selected' : '' }}>Technical Detailing</option>
                            <option value="Technical_Checking" {{ old('work_descipline', $task->work_descipline ?? '') == 'Technical_Checking' ? 'selected' : '' }}>Technical Checking</option>
                            <option value="General_Technical" {{ old('work_descipline', $task->work_descipline ?? '') == 'General_Technical' ? 'selected' : '' }}>General Technical</option>
                            <option value="General_Non_Technical" {{ old('work_descipline', $task->work_descipline ?? '') == 'General_Non_Technical' ? 'selected' : '' }}>General Non Technical</option>
                        </select>
                        @if ($errors->has('work_descipline'))
                          <span class="text-danger">{{ $errors->first('work_descipline') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Production Type <sup>*</sup></label>
                        <select name="production_type" id="production_type" class="form-control" tabindex="5">
                            <option value="">-- Select Production Type --</option>
                            <option value="Main_Steel" {{ old('production_type', $task->production_type ?? '') == 'Main_Steel' ? 'selected' : '' }}>Main Steel</option>
                            <option value="Misc_Steel" {{ old('production_type', $task->production_type ?? '') == 'Misc_Steel' ? 'selected' : '' }}>Misc Steel</option>
                            <option value="General" {{ old('production_type', $task->production_type ?? '') == 'General' ? 'selected' : '' }}>General</option>
                        </select>
                        @if ($errors->has('production_type'))
                        <span class="text-danger">{{ $errors->first('production_type') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Production Stage <sup>*</sup></label>
                        <select name="production_stage" id="production_stage" class="form-control" tabindex="6">
                            <option value="">-- Select Production Stage --</option>
                            <option value="OFA" {{ old('production_stage', $task->production_stage ?? '') == 'OFA' ? 'selected' : '' }}>OFA</option>
                            <option value="FAB" {{ old('production_stage', $task->production_stage ?? '') == 'FAB' ? 'selected' : '' }}>FAB</option>
                            <option value="General" {{ old('production_stage', $task->production_stage ?? '') == 'General' ? 'selected' : '' }}>General</option>
                        </select>
                        @if ($errors->has('production_stage'))
                          <span class="text-danger">{{ $errors->first('production_stage') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer" align="center">
            <button type="submit" id="submitBtn" class="btn btn-primary btn-flat" tabindex="7"><i class="fas fa-save"></i> Save</button>
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

</style>
@endsection

@section('scripts')
<script>
$(function () {
    bsCustomFileInput.init();
    $.validator.setDefaults({
        submitHandler: function (form) {
            $('#submitBtn').prop('disabled', true); // Disable the submit button
            form.submit();
        }
    });
});
</script>
@endsection