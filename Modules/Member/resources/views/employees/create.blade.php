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
        <h3 class="card-title"><i class="fas fa-user-tag"></i> {{$page_title}}</h3>
        <a class="btn btn-dark btn-sm btn-flat float-right" href="{{route('employees.index')}}"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
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
    <form id="EmployeeForm" method="post" action="{{ route('employees.update') }}" enctype="multipart/form-data">
    @csrf
        <input type="hidden" name="id" value="{{ $employee->id ?? '' }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Employee Code <sup>*</sup></label>
                        <input type="text" name="employee_code" class="form-control" value="{{ old('employee_code', $employee->employee_code ?? $employee_code) }}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Name <sup>*</sup></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $employee->name ?? '') }}" tabindex="1">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" class="form-control" tabindex="2">
                            <option value="">-- Select --</option>
                            <option value="Male" {{ old('gender', $employee->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $employee->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $employee->gender ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <div class="input-group date" id="date_of_birth" data-target-input="nearest">
                            <input type="text" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', isset($employee->date_of_birth) ? \Carbon\Carbon::parse ($employee->date_of_birth)->format('d/m/Y') : '') }}" tabindex="3">
                            <div class="input-group-append" data-target="#date_of_birth" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone ?? '') }}" tabindex="4">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email ?? '') }}" tabindex="5">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="2" tabindex="6">{{ old('address', $employee->address ?? '') }}</textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Designation</label>
                        <input type="text" name="designation" class="form-control" value="{{ old('designation', $employee->designation ?? '') }}" tabindex="7">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Joining Date</label>
                        <div class="input-group date" id="joining_date" data-target-input="nearest">
                            <input type="text" name="joining_date" class="form-control" value="{{ old('joining_date', isset($employee->joining_date) ? \Carbon\Carbon::parse ($employee->joining_date)->format('d/m/Y') : '') }}" tabindex="8">
                            <div class="input-group-append" data-target="#joining_date" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" tabindex="9">
                            <option value="Active" {{ old('status', $employee->status ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status', $employee->status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
					<div class="form-group">
						<label for="customFile">Image(200 X 50)</label>
                        	<div class="input-group">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="image" accept="image/png, image/jpeg, image/jpg, image/gif, image/webp" tabindex="10">
                                <label class="custom-file-label" for="customFile">Choose file</label>
							</div>
                        </div>
						<div id="photo_preview" class="mt-2">
						    @if(!empty($employee->image))
						        <img src="{{asset('storage/employee_logos/'.$employee->image)}}" alt="Customer Photo" style="width: 200px; height: 100px;">
						    @else
                                <img src="{{asset('uploads/avatar.png')}}" alt="Employee Photo" style="width: 200px; height: 50px;">
                            @endif
                        </div><br>
					</div>
				</div>
            </div>
        </div>
        <div class="card-footer" align="center">
            <button type="submit" id="submitBtn" class="btn btn-primary btn-flat" tabindex="11"><i class="fas fa-save"></i> Save</button>
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
    $('#date_of_birth').datetimepicker({
        format: 'DD/MM/YYYY'
    });
    $('#joining_date').datetimepicker({
        format: 'DD/MM/YYYY'
    });
});
</script>
@endsection