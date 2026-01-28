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
            <a class="btn btn-dark btn-sm btn-flat float-right" href="{{ route('users.index') }}">
                <i class="fas fa-arrow-alt-circle-left"></i> Back
            </a>
             @if ($errors->any())
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    toastr.error(`{!! implode('<br>', $errors->all()) !!}`, 'Validation Error');
                });
            </script>
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
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Password <sup>*</sup></label>
                        <input type="password" name="password" id="password" tabindex="2" class="form-control" value="{{ $user->password ?? '' }}">
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

                {{-- Name Text Field (Admin & Client) --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Name <sup>*</sup></label>
                        <input type="text" name="name" id="name" tabindex="4" class="form-control"
                            value="{{ old('name', $user->name ?? '') }}">
                        @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Client Company dropdown for Client role --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Client Company <sup>*</sup></label>
                        <select name="company_id" id="company_id" class="form-control" tabindex="5">
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
                                <input type="file" class="custom-file-input" id="customFile" tabindex="6" name="avatar">
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
            <button type="submit" id="submitBtn" tabindex="7" class="btn btn-primary btn-flat">
                <i class="fas fa-save"></i> Save
            </button>
            <button type="reset" value="Reset" id="resetbtn" tabindex="8" class="btn btn-secondary btn-flat">
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

</script>
@endsection
