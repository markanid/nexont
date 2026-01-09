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
                    <li class="breadcrumb-item"><a href="{{route('projections.index')}}">Projection</a></li>
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
        <h3 class="card-title"><i class="fas fa-chart-line"></i> {{$page_title}}</h3>
        <a class="btn btn-dark btn-sm btn-flat float-right" href="{{route('projections.show', $projection_id)}}"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
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
    <form id="ProjectionForm" method="post" action="{{ route('runningprojects.update') }}" enctype="multipart/form-data">
    @csrf
        @if($runningProject)
            <input type="hidden" name="id" value="{{ $runningProject->id }}">
        @endif
        <input type="hidden" name="projection_id" value="{{ $projection_id  }}">
        <input type="hidden" name="created_by" value="{{ Auth::user()->id }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nexont Project ID <sup>*</sup></label>
                        <select name="project_id" id="project_id" class="form-control" tabindex="1">
                            <option value="">-- Select Project_Id --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ old('project_id', $runningProject->project_id ?? '') == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_code }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('project_id'))
                          <span class="text-danger">{{ $errors->first('project_id') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Project Name <sup>*</sup></label>
                        <select id="project_name" class="form-control" tabindex="2">
                            <option value="">-- Select Project_Name --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ old('project_id', $runningProject->project_id ?? '') == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Client</label>
                        <input type="text" id="client" class="form-control" readOnly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Projection</label>
                        <input type="text" name="projection_value" class="form-control" value="{{ old('projection_value', $runningProject->projection_value ?? '') }}" tabindex="3">
                        @if ($errors->has('projection_value'))
                          <span class="text-danger">{{ $errors->first('projection_value') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Work Type<sup>*</sup></label>
                        <select id="type" name="type" class="form-control" tabindex="4">
                            <option value="">-- Select Status --</option>
                            <option value="Approval" {{ old('type', $runningProject->type ?? '') == 'Approval' ? 'selected' : '' }}> Approval </option>
                            <option value="Fabrication" {{ old('type', $runningProject->type ?? '') == 'Fabrication' ? 'selected' : '' }}> Fabrication </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Billing Description</label>
                        <input type="text" id="billing_desc" class="form-control" name="billing_desc" value="{{ old('billing_desc', $runningProject->billing_desc ?? '') }}"  tabindex="5">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Work Status<sup>*</sup></label>
                        <select id="status" name="status" class="form-control" tabindex="6">
                            <option value="">-- Select Status --</option>
                            <option value="Completed" {{ old('status', $runningProject->status ?? '') == 'Completed' ? 'selected' : '' }}> Completed </option>
                            <option value="In Progress" {{ old('status', $runningProject->status ?? '') == 'In Progress' ? 'selected' : '' }}> In Progress </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Remarks</label>
                        <input type="text" id="remarks" class="form-control" name="remarks" value="{{ old('remarks', $runningProject->remarks ?? '') }}" tabindex="7">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>PO Details</label>
                        <input type="text" id="po_details" class="form-control" readOnly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Invoice Details</label>
                        <input type="text" id="invoice_details" class="form-control" readOnly>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer" align="center">
            <button type="submit" id="submitBtn" class="btn btn-primary btn-flat" tabindex="8"><i class="fas fa-save"></i> Save</button>
             <button type="reset" value="Reset" id="resetbtn" class="btn btn-secondary  btn-flat" tabindex="18"><i class="fas fa-undo-alt"></i> Reset</button>
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
});

$(document).ready(function () {
    @if($runningProject)
        loadProjectDetails({{ $runningProject->project_id }});
    @endif
    function loadProjectDetails(projectId) {
        if (!projectId) return;

        // sync both selects
        $('#project_id, #project_name').val(projectId);

        $.ajax({
            url: "{{ url('projects') }}/" + projectId + "/details",
            type: "GET",
            success: function (res) {
                $('#client').val(res.client ?? '');
                $('#po_details').val(res.po ?? '');
            }
        });
    }

    $('#project_id, #project_name').on('change', function () {
        loadProjectDetails($(this).val());
    });

});

</script>
@endsection