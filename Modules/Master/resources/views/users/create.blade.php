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
                    <li class="breadcrumb-item"><a href="{{route('users.index')}}">{{ $title }}</a></li>
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
        <h3 class="card-title"><i class="fas fa-user-cog"></i> {{$page_title}}</h3>
        <a class="btn btn-dark btn-sm btn-flat float-right" href="{{route('users.index')}}"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif
    </div>
    <form id="addUser" method="post" action="{{ route('users.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id ?? '' }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                	<div class="form-group">
    					<label for="customFile">User Photo(150x150)</label>
    				
    					<div class="input-group">
    						<div class="custom-file">
    							<input type="file" class="custom-file-input" id="customFile" tabindex="1" name="avatar">
    							<label class="custom-file-label" for="customFile">Choose file</label>
    						</div>
    						
    						@if ($errors->has('avatar'))
                              <span class="text-danger">{{ $errors->first('avatar') }}</span>
                            @endif
    					</div>
                        <div id="photo_preview" class="mt-2">
						    @if(!empty($user->avatar))
						        <img src="{{asset('storage/user_logos/'.$user->avatar)}}" alt="User Photo" style="width: 200px; height: 200px;">
						    @else
                                <img src="{{asset('uploads/avatar.png')}}" alt="User Photo" style="width: 150px; height: 150px;">
                            @endif
                        </div>
    				</div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email ID<sup>*</sup></label>
                        <input type="email" name="email" id="email" tabindex="2" class="form-control" autocomplete="on" value="{{ old('name', $user->email ?? '') }}">
                        @if ($errors->has('email'))
                          <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>User Type<sup>*</sup></label>
                        <select name="role" id="role" class="form-control" tabindex="3">
                            <option value="">-- Select Role --</option>

                            {{-- If no users exist --}}
                            @if (!$hasUsers)
                                @if (!$hasAdminUser || (isset($user) && $user->role === 'Admin'))
                                    <option value="Admin" {{ (old('role', $user->role ?? '') == 'Admin') ? 'selected' : '' }}>Admin</option>
                                @endif
                            @else
                                {{-- If admin doesn't already exist OR we're editing the current Admin --}}
                                @if (!$hasAdminUser || (isset($user) && $user->role === 'Admin'))
                                    <option value="Admin" {{ (old('role', $user->role ?? '') == 'Admin') ? 'selected' : '' }}>Admin</option>
                                @endif
                                <option value="Project Manager" {{ (old('role', $user->role ?? '') == 'Project Manager') ? 'selected' : '' }}>Project Manager</option>
                                <option value="PMO" {{ (old('role', $user->role ?? '') == 'PMO') ? 'selected' : '' }}>PMO</option>
                                <option value="Sales Manager" {{ (old('role', $user->role ?? '') == 'Sales Manager') ? 'selected' : '' }}>Sales Manager</option>
                                <option value="Accountant" {{ (old('role', $user->role ?? '') == 'Accountant') ? 'selected' : '' }}>Accountant</option>

                                @if ($hasClientCompany)
                                    <option value="Client" {{ (old('role', $user->role ?? '') == 'Client') ? 'selected' : '' }}>Client</option>
                                @endif
                            @endif
                        </select>
                        @if ($errors->has('role'))
                          <span class="text-danger">{{ $errors->first('role') }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-4" id="nameTextGroup" style="display:none;">
                    <div class="form-group">
                        <label>Name<sup>*</sup></label>
                        <input type="text" name="name" id="name" tabindex="4" class="form-control" value="{{ old('name', $user->name ?? '') }}">
                        @if ($errors->has('name'))
                          <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-4" id="nameDropdownGroup" style="display:none;">
                <select name="employee_name" class="form-control" tabindex="4">
                    <option value="">-- Select Employee --</option>
                    {{-- @foreach($employees as $employee)
                        <option value="{{ $employee->name }}" {{ (old('name', $user->name ?? '') == $employee->name) ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach --}}
                </select>
                @error('name')
                    <p class="invalid-feedback">{{ $message }}</p>
                @enderror
                </div>

                <div class="col-md-4" id="clientCompanyBox" style="display:none;">
                    <div class="form-group">
                        <label>Client Company<sup>*</sup></label>
                        <select name="company_id" id="company_id" class="form-control" tabindex="5">
                            <option value="">-- Select Client Company --</option>
                            @foreach($clientCompanies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id', $user->company_id ?? '') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer" align="center">
            <button type="submit" id="submitBtn" tabindex="6" class="btn btn-primary  btn-flat"><i class="fas fa-save"></i> Save</button>
            <button type="reset" value="Reset" id="resetbtn" tabindex="7" class="btn btn-secondary  btn-flat"><i class="fas fa-undo-alt"></i> Reset</button>
            
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    let today = new Date();
    let formattedDate = ("0" + today.getDate()).slice(-2) + '/' + ("0" + (today.getMonth() + 1)).slice(-2) + '/' + today.getFullYear();
    $('#date').val(formattedDate);
    
    bsCustomFileInput.init();
    $('#customFile').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass("selected").html(fileName);
        
        var file = this.files[0];
        if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#photo_preview').html('<img src="' + e.target.result + '" alt="User Photo" style="width: 150px; height: 150px;">');
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const clientCompanyBox = document.getElementById('clientCompanyBox');
    const nameTextGroup = document.getElementById('nameTextGroup');
    const nameDropdownGroup = document.getElementById('nameDropdownGroup');

    function toggleClientCompanyBox() {
        clientCompanyBox.style.display = (roleSelect.value === 'Client') ? 'flex' : 'none';
    }

    function toggleNameFields() {
        const role = roleSelect.value;
        if (role === 'Admin' || role === 'Client') {
            nameTextGroup.style.display = 'flex';    // Show text input
            nameDropdownGroup.style.display = 'none'; // Hide dropdown
        } else if (role) {
            nameTextGroup.style.display = 'none';    // Hide text input
            nameDropdownGroup.style.display = 'flex';  // Show dropdown
        } else {
            // No role selected
            nameTextGroup.style.display = 'none';
            nameDropdownGroup.style.display = 'none';
        }
    }

    // Initial load
    toggleClientCompanyBox();
    toggleNameFields();

    // On change
    roleSelect.addEventListener('change', function () {
        toggleClientCompanyBox();
        toggleNameFields();
    });
});
</script>
@endsection