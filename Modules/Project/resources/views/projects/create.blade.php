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
    <form id="ProjectForm" method="post" action="{{ route('projects.update') }}" enctype="multipart/form-data">
    @csrf
        <input type="hidden" name="id" value="{{ $project->id ?? '' }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nexont Project ID <sup>*</sup></label>
                        <input type="text" name="project_code" id="project_code" class="form-control" value="{{ old('project_code', $project->project_code ?? $project_code) }}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Project Name <sup>*</sup></label>
                        <input type="text" name="project_name" id="project_name" class="form-control" value="{{ old('project_name', $project->project_name ?? '') }}" tabindex="1">
                        @if ($errors->has('project_name'))
                          <span class="text-danger">{{ $errors->first('project_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Client Company Name <sup>*</sup></label>
                        <select name="company_id" id="company_id" class="form-control" tabindex="2">
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
                        <label>Client POC <sup>*</sup></label>
                        <select name="client_id" id="client_id" class="form-control" tabindex="3">
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
                        <label>Nexont PM <sup>*</sup></label>
                        <select name="project_manager_id" id="project_manager_id" class="form-control" tabindex="4">
                            <option value="">-- Select Project-Manager --</option>
                            @foreach($pms as $project_manager)
                                <option value="{{ $project_manager->id }}" {{ old('project_manager_id', $project_manager->project_manager_id ?? '') == $project_manager->id ? 'selected' : '' }}>
                                    {{ $project_manager->name }}
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
                        <select name="sales_manager_id" id="sales_manager_id" class="form-control" tabindex="5">
                            <option value="">-- Select Sales-Manager --</option>
                            @foreach($sms as $sales_manager)
                                <option value="{{ $sales_manager->id }}" {{ old('sales_manager_id', $sales_manager->sales_manager_id ?? '') == $sales_manager->id ? 'selected' : '' }}>
                                    {{ $sales_manager->name }}
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
                        <label>Client Project ID</label>
                        <input type="text" name="project_cid" id="project_cid" class="form-control" value="{{ old('project_cid', $project->project_cid ?? '') }}" tabindex="6">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Purchase Order# </label>
                        <input type="text" name="po" id="po" class="form-control" value="{{ old('po', $project->po ?? '') }}" tabindex="7">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="status" class="form-control" tabindex="8">
                             @foreach(['Planned', 'On Going', 'Completed', 'On Hold', 'Cancelled'] as $status)
                                <option value="{{ $status }}" {{ old('status', $project->status ?? '') == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
          
                <div class="col-md-6">
                    <div class="group-box">
                        <label class="group-title">Approval Schedule [Weeks]</label>
                        <div class="row mt-2">
                            <div class="col-6">
                                <label class="small">Main Steel</label>
                                <input type="text" name="apr_main_steel" id="apr_main_steel" class="form-control"
                                    value="{{ old('apr_main_steel', $project->apr_main_steel ?? '') }}" tabindex="9">
                            </div>
                            <div class="col-6">
                                <label class="small">Misc Steel</label>
                                <input type="text" name="apr_misc_steel" id="apr_misc_steel" class="form-control"
                                    value="{{ old('apr_misc_steel', $project->apr_misc_steel ?? '') }}" tabindex="10">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="group-box">
                        <label class="group-title">PO Value</label>
                        <div class="row mt-2">
                            <div class="col-3">
                                <label class="small">Main SD</label>
                                <input type="text" name="po_main_sd" id="po_main_sd" class="form-control" value="{{ old('po_main_sd', $project->po_main_sd ?? '0.00') }}" tabindex="11">
                            </div>
                            <div class="col-3">
                                <label class="small">Misc SD</label>
                                <input type="text" name="po_misc_sd" id="po_misc_sd" class="form-control" value="{{ old('po_misc_sd', $project->po_misc_sd ?? '0.00') }}" tabindex="12">
                            </div>
                            <div class="col-3">
                                <label class="small">Engineering</label>
                                <input type="text" name="po_engineering" id="po_engineering" class="form-control" value="{{ old('po_engineering', $project->po_engineering ?? '0.00') }}" tabindex="13">
                            </div>
                            <div class="col-3 mb-2">
                                <label class="small">Currency</label>
                                <select name="po_currency" id="po_currency" class="form-control">
                                    @foreach($currencies as $code => $name)
                                        <option value="{{ $code }}"
                                            {{ old('po_currency', $project->po_currency ?? 'INR') == $code ? 'selected' : '' }}>
                                            {{ $code }} - {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Start Date</label>
                        <div class="input-group date" id="start_date" data-target-input="nearest">
                            <input type="text" name="start_date" class="form-control" value="{{ old('start_date', isset($project->start_date) ? \Carbon\Carbon::parse ($project->start_date)->format('d/m/Y') : '') }}" tabindex="14">
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
                        <label>Kitty Value</label>
                        <input type="text" name="kitty" id="kitty" class="form-control" value="{{ old('kitty', $project->kitty ?? '0.00') }}" tabindex="15" readOnly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Change Order Value</label>
                        <input type="text" name="covalue" id="covalue" class="form-control" value="{{ old('covalue', $project->covalue ?? '0.00') }}" tabindex="16" readOnly>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer" align="center">
            <button type="submit" id="submitBtn" class="btn btn-primary btn-flat" tabindex="17"><i class="fas fa-save"></i> Save</button>
             <button type="reset" value="Reset" id="resetbtn" class="btn btn-secondary  btn-flat" tabindex="18"><i class="fas fa-undo-alt"></i> Reset</button>
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
});

$(function () {

    function calculateKitty() {
        let main = parseFloat($("#po_main_sd").val()) || 0;
        let misc = parseFloat($("#po_misc_sd").val()) || 0;
        let eng  = parseFloat($("#po_engineering").val()) || 0;
        let co   = parseFloat($("#covalue").val()) || 0;

        let total = main + misc + eng + co;

        $("#kitty").val(total.toFixed(2));
    }

    // Trigger calculation when values change
    $("#po_main_sd, #po_misc_sd, #po_engineering, #covalue").on("input", calculateKitty);

    // Run once on page load (for edit mode)
    calculateKitty();

});
</script>
@endsection