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
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">
                        @if(auth()->user()->role != 'Client')
                            <a href="{{ route('users.index') }}">{{ $title }}</a>
                        @else
                            {{ $title }}
                        @endif
                    </li>
                    <li class="breadcrumb-item active">{{ $page_title }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@section('body')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-cog"></i> {{ $page_title }}</h3>
        @if(auth()->user()->role != 'Client')
            <a class="btn btn-dark btn-sm btn-flat float-right" href="{{ route('users.index') }}">
                <i class="fas fa-arrow-alt-circle-left"></i> Back
            </a>
        @endif
    </div>

    <form id="addUser" method="post" action="{{ route('users.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">

        <div class="card-body">
            <div class="row">

                {{-- Email --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email ID <sup>*</sup></label>
                        <input type="email" name="email" id="email" tabindex="1"
                            class="form-control" autocomplete="on" value="{{ old('email', $user->email ?? '') }}">
                        @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Password fields ONLY on create --}}
                @if(!$isEdit)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Password <sup>*</sup></label>
                            <input type="password" name="password" id="password" tabindex="2" class="form-control">
                            @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Confirm Password <sup>*</sup></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" tabindex="3" class="form-control">
                            @error('password_confirmation')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                @endif

                {{-- Role Dropdown --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label>User Type <sup>*</sup></label>
                        <select name="role" id="role" class="form-control" tabindex="4">
                            <option value="">-- Select Role --</option>

                            @if($isEdit && $isAdminEdit)
                                {{-- Editing Admin → ONLY Admin option --}}
                                <option value="Admin" selected>Admin</option>
                            @else
                                {{-- Create OR Edit non-admin user --}}
                                @php $selectedRole = old('role', $user->role ?? ''); @endphp
                                <option value="Project Manager" {{ $selectedRole == 'Project Manager' ? 'selected' : '' }}>Project Manager</option>
                                <option value="PMO" {{ $selectedRole == 'PMO' ? 'selected' : '' }}>PMO</option>
                                <option value="Sales Manager" {{ $selectedRole == 'Sales Manager' ? 'selected' : '' }}>Sales Manager</option>
                                <option value="Accountant" {{ $selectedRole == 'Accountant' ? 'selected' : '' }}>Accountant</option>
                                @if($hasClientCompany)
                                    <option value="Client" {{ $selectedRole == 'Client' ? 'selected' : '' }}>Client</option>
                                @endif
                            @endif
                        </select>
                        @error('role')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Name Text Field (Admin & Client) --}}
                <div class="col-md-4" id="nameTextGroup" style="display:none;">
                    <div class="form-group">
                        <label>Name <sup>*</sup></label>
                        <input type="text" name="name" id="name" tabindex="5" class="form-control"
                            value="{{ old('name', in_array($user->role ?? '', ['Admin', 'Client']) ? $user->name ?? '' : '') }}">
                        @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Employee Dropdown (for other roles) --}}
                <div class="col-md-4" id="nameDropdownGroup" style="display:none;">
                    <div class="form-group">
                        <label>Employee Name <sup>*</sup></label>
                        <select name="employee_name" class="form-control" tabindex="6">
                            <option value="">-- Select Employee --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->name }}"
                                    {{ old('employee_name', (!in_array($user->role ?? '', ['Admin','Client']) ? $user->name ?? '' : '')) == $employee->name ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_name')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Client Company dropdown for Client role --}}
                <div class="col-md-4" id="clientCompanyBox" style="display:none;">
                    <div class="form-group">
                        <label>Client Company <sup>*</sup></label>
                        <select name="company_id" id="company_id" class="form-control" tabindex="7">
                            <option value="">-- Select Client Company --</option>
                            @foreach($clientCompanies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id', $user->company_id ?? '') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_id')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Avatar upload --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="customFile">User Photo (150x150)</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" tabindex="8" name="avatar">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        @error('avatar')<span class="text-danger">{{ $message }}</span>@enderror
                        <div id="photo_preview" class="mt-2">
                            @if(!empty($user->avatar))
                                <img src="{{ asset('storage/user_logos/'.$user->avatar) }}" alt="User Photo" style="width: 150px; height: 150px;">
                            @else
                                <img src="{{ asset('uploads/avatar.png') }}" alt="User Photo" style="width: 150px; height: 150px;">
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer text-center">
            <button type="submit" id="submitBtn" tabindex="9" class="btn btn-primary btn-flat">
                <i class="fas fa-save"></i> Save
            </button>
            <button type="reset" value="Reset" id="resetbtn" tabindex="10" class="btn btn-secondary btn-flat">
                <i class="fas fa-undo-alt"></i> Reset
            </button>
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
                $('#photo_preview').html('<img src="'+e.target.result+'" alt="User Photo" style="width: 150px; height: 150px;">');
            }
            reader.readAsDataURL(file);
        }
    });

    $.validator.setDefaults({
        submitHandler: function(form) {
            $('#submitBtn').prop('disabled', true);
            form.submit();
        }
    });
});

// Role-based field toggle
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const clientCompanyBox = document.getElementById('clientCompanyBox');
    const nameTextGroup = document.getElementById('nameTextGroup');
    const nameDropdownGroup = document.getElementById('nameDropdownGroup');

    function toggleFields() {
        const role = roleSelect.value;
        if (!role) {
            nameTextGroup.style.display = 'none';
            nameDropdownGroup.style.display = 'none';
            clientCompanyBox.style.display = 'none';
            return;
        }

        if (role === 'Client') {
            nameTextGroup.style.display = 'block';
            nameDropdownGroup.style.display = 'none';
            clientCompanyBox.style.display = 'block';
        } else if (role === 'Admin') {
            nameTextGroup.style.display = 'block';
            nameDropdownGroup.style.display = 'none';
            clientCompanyBox.style.display = 'none';
        } else {
            nameTextGroup.style.display = 'none';
            nameDropdownGroup.style.display = 'block';
            clientCompanyBox.style.display = 'none';
        }
    }

    toggleFields();
    roleSelect.addEventListener('change', toggleFields);
});
</script>
@endsection
