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
                    <li class="breadcrumb-item"><a href="{{route('companies.index')}}">{{$title}}</a></li>
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
        <h3 class="card-title"><i class="far fa-building"></i> {{$page_title}}</h3>
        <a class="btn btn-dark btn-sm btn-flat float-right" href="{{route('companies.index')}}"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
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

    <form id="EditCompany" method="post" action="{{ route('companies.update') }}" enctype="multipart/form-data">
    @csrf
        <input type="hidden" id="id" name="id" value="{{ $company->id ?? '' }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Company<sup>*</sup></label>
                        <input type="text" name="name" tabindex="1" class="form-control" value="{{ !empty($company->name) ? $company->name : '' }}">
                        @if ($errors->has('name'))
                          <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" tabindex="2" class="form-control" value="{{ !empty($company->phone) ? $company->phone : '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" tabindex="3" class="form-control" autocomplete="off" value="{{ !empty($company->email) ? $company->email : '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Website</label>
                        <input type="text" name="website" tabindex="4" class="form-control" value="{{ !empty($company->website) ? $company->website : '' }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" tabindex="5" class="form-control" rows="3">{{ !empty($company->address) ? $company->address : '' }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" id="type" tabindex="6" class="form-control">
                            <option value="">Select Type</option>
                            <option value="company" {{ !empty($company->type) && $company->type == 'company' ? 'selected' : '' }}>Company</option>
                            <option value="client" {{ !empty($company->type) && $company->type == 'client' ? 'selected' : '' }}>Client</option>
                        </select>
                    </div>
                </div> 
                <div class="col-md-6" id="client_id_group" style="display: none;">
                    <div class="form-group">
                        <label>Client ID</label>
                        <input type="text" name="client_id" tabindex="7" class="form-control" value="{{ !empty($company->client_id) ? $company->client_id : '' }}">
                    </div>
                </div>               
                <div class="col-md-6">
					<div class="form-group">
						<label for="customFile">Image(200 X 50)</label>
                        <div class="input-group">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" tabindex="8" name="logo_path" accept="image/png, image/jpeg, image/jpg, image/gif, image/webp">
                                <label class="custom-file-label" for="customFile">Choose file</label>
							</div>
                        </div>
						<div id="photo_preview" class="mt-2">
						    @if(!empty($company->logo_path))
						        <img src="{{asset('storage/company_logos/'.$company->logo_path)}}" alt="Company Photo" style="width: 200px; height: 200px;">
						    @else
                                <img src="{{asset('uploads/avatar.png')}}" alt="Company Photo" style="width: 150px; height: 150px;">
                            @endif
                        </div><br>
					</div>
				</div>
            </div>
        </div>
        <div class="card-footer" align="center">
            <button type="submit" id="submitBtn" tabindex="9" class="btn btn-primary btn-flat"><i class="fas fa-save"></i> Save</button>
             <button type="reset" value="Reset" id="resetbtn" tabindex="10" class="btn btn-secondary  btn-flat"><i class="fas fa-undo-alt"></i> Reset</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('type');
        const clientIdGroup = document.getElementById('client_id_group');

        function toggleClientId() {
            const selected = typeSelect.value;
            if (selected === 'client') {
                clientIdGroup.style.display = 'block';
            } else {
                clientIdGroup.style.display = 'none';
            }
        }
        toggleClientId();

        typeSelect.addEventListener('change', toggleClientId);
    });
</script>
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
                $('#photo_preview').html('<img src="' + e.target.result + '" alt="Company Photo" style="width: 150px; height: 150px;">');
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