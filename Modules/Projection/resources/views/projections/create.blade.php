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
                    <li class="breadcrumb-item"><a href="{{route('employees.index')}}">Employee</a></li>
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
        <a class="btn btn-dark btn-sm btn-flat float-right" href="{{route('projections.index')}}"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
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
    <form id="EmployeeForm" method="post" action="{{ route('projections.update') }}" enctype="multipart/form-data">
    @csrf
        <input type="hidden" name="id" value="{{ $projection->id ?? '' }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Projection Month <sup>*</sup></label>
                        <select name="month" class="form-control"  tabindex="1">
                            <option value="">Select Month</option>
                            @foreach(range(1,12) as $m)
                                @php $monthName = date('F', mktime(0, 0, 0, $m, 1)); @endphp
                                <option value="{{ $monthName }}"
                                    {{ old('month', $projection->month) === $monthName ? 'selected' : '' }}>
                                    {{ $monthName }}
                                </option>
                            @endforeach
                        </select>                                                               
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Projection Year <sup>*</sup></label>
                        <input type="text" name="year" class="form-control" value="{{ old('year', $projection->year ?? '') }}" tabindex="2">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer" align="center">
            <button type="submit" id="submitBtn" class="btn btn-primary btn-flat" tabindex="3"><i class="fas fa-save"></i> Save</button>
             <button type="reset" value="Reset" id="resetbtn" class="btn btn-secondary  btn-flat" tabindex="12"><i class="fas fa-undo-alt"></i> Reset</button>
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
</script>
@endsection