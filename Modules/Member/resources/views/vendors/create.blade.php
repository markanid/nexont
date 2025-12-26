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
                    <li class="breadcrumb-item"><a href="{{route('vendors.index')}}">Vendor</a></li>
                    <li class="breadcrumb-item active">{{$title}}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@section('body')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-tie"></i> {{$page_title}}</h3>
        <a class="btn btn-dark btn-sm btn-flat float-right" href="{{route('vendors.index')}}"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
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
    <form id="EditCompany" method="post" action="{{ route('vendors.update') }}" enctype="multipart/form-data">
    @csrf
        <input type="hidden" id="id" name="id" value="{{ $vendor->id ?? '' }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Vendor Name<sup>*</sup></label>
                        <input type="text" name="company_name" id="company_name" tabindex="1" class="form-control" value="{{ !empty($vendor->company_name) ? $vendor->company_name : '' }}">
                        @if ($errors->has('company_name'))
                          <span class="text-danger">{{ $errors->first('company_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Phone<sup>*</sup></label>
                        <input type="text" name="company_phone" id="company_phone" tabindex="2" class="form-control" value="{{ !empty($vendor->company_phone) ? $vendor->company_phone : '' }}">
                        @if ($errors->has('company_phone'))
                          <span class="text-danger">{{ $errors->first('company_phone') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" id="address" tabindex="3" class="form-control" rows="3">{{ !empty($vendor->address) ? $vendor->address : '' }}</textarea>
                    </div>
                </div> 
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="email" tabindex="4" class="form-control" value="{{ !empty($vendor->email) ? $vendor->email : '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>GST No.</label>
                        <input type="text" name="gst_number" id="gst_number" tabindex="5" class="form-control" value="{{ !empty($vendor->gst_number) ? $vendor->gst_number : '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Website</label>
                        <input type="text" name="website" id="website" tabindex="6" class="form-control" value="{{ !empty($vendor->website) ? $vendor->website : '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Contact Person</label>
                        <input type="text" name="rep_name" id="rep_name" tabindex="7" class="form-control" value="{{ !empty($vendor->rep_name) ? $vendor->rep_name : '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Contact Person Number</label>
                        <input type="text" name="rep_phone" id="rep_phone" tabindex="8" class="form-control" value="{{ !empty($vendor->rep_phone) ? $vendor->rep_phone : '' }}">
                        @if ($errors->has('rep_phone'))
                          <span class="text-danger">{{ $errors->first('rep_phone') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
					<div class="form-group">
						<label for="customFile">Image(200 X 50)</label>
                        	<div class="input-group">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" tabindex="10" name="logo" accept="image/png, image/jpeg, image/jpg, image/gif, image/webp">
                                <label class="custom-file-label" for="customFile">Choose file</label>
							</div>
                        </div>
						<div id="photo_preview" class="mt-2">
						    @if(!empty($vendor->logo))
						        <img src="{{asset('storage/vendor_logos/'.$vendor->logo)}}" alt="Vendor Photo" style="width: 200px; height: 50px;">
						    @else
                                <img src="{{asset('uploads/avatar.png')}}" alt="Vendor Photo" style="width: 200px; height: 50px;">
                            @endif
                        </div><br>
					</div>
				</div>
            </div>
        </div>
        <div class="card-footer" align="center">
            <button type="submit" id="submitBtn" tabindex="11" class="btn btn-primary btn-flat"><i class="fas fa-save"></i> Save</button>
             <button type="reset" value="Reset" id="resetbtn" tabindex="12" class="btn btn-secondary  btn-flat"><i class="fas fa-undo-alt"></i> Reset</button>
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