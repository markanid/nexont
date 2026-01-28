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
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone ?? '') }}" tabindex="2">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email ?? '') }}" tabindex="3">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Password <sup>*</sup></label>
                        <input type="password" name="password" class="form-control" tabindex="4">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Confirm Password <sup>*</sup></label>
                        <input type="password" name="password_confirmation" class="form-control" tabindex="5">
                    </div>
                </div>

                {{-- Role Dropdown --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Designation <sup>*</sup></label>
                        <select name="designation" id="designation" class="form-control" tabindex="4">
                            <option value="">-- Select Designation --</option>

                            @if($isEdit && $isAdminEdit)
                                {{-- Editing Admin → ONLY Admin option --}}
                                <option value="Admin" selected>Admin</option>
                            @else
                                {{-- Create OR Edit non-admin user --}}
                                @php $selectedRole = old('role', $employee->designation ?? ''); @endphp
                                <option value="Project Manager" {{ $selectedRole == 'Project Manager' ? 'selected' : '' }}>Project Manager</option>
                                <option value="PMO" {{ $selectedRole == 'PMO' ? 'selected' : '' }}>PMO</option>
                                <option value="Sales Manager" {{ $selectedRole == 'Sales Manager' ? 'selected' : '' }}>Sales Manager</option>
                                <option value="Accountant" {{ $selectedRole == 'Accountant' ? 'selected' : '' }}>Accountant</option>
                                <option value="Employee" {{ $selectedRole == 'Employee' ? 'selected' : '' }}>Employee</option>
                            @endif
                        </select>
                        @error('designation')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" tabindex="7">
                            <option value="Active" {{ old('status', $employee->status ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status', $employee->status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
					<div class="form-group">
						<label for="customFile">Image(150x150)</label>
                        	<div class="input-group">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="image" accept="image/png, image/jpeg, image/jpg, image/gif, image/webp" tabindex="8">
                                <label class="custom-file-label" for="customFile">Choose file</label>
							</div>
                        </div>
						<div id="photo_preview" class="mt-2">
						    @if(!empty($employee->image))
						        <img src="{{ asset('storage/employee_logos/'.$employee->image) }}" alt="Employee Photo" style="width: 150px; height: 150px;">
						    @else
                                <img src="{{asset('uploads/avatar.png')}}" alt="Employee Photo" style="width: 150px; height: 150px;">
                            @endif
                        </div><br>
					</div>
				</div>

            </div>
        </div>
        <div class="card-footer" align="center">
            <button type="submit" id="submitBtn" class="btn btn-primary btn-flat" tabindex="9"><i class="fas fa-save"></i> Save</button>
             <button type="reset" value="Reset" id="resetbtn" class="btn btn-secondary  btn-flat" tabindex="10"><i class="fas fa-undo-alt"></i> Reset</button>
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