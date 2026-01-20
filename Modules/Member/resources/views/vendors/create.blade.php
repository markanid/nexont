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
                        <label>EIN No.</label>
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
                        <label>W-9 Form<sup>*</sup></label>
                        <select name="w9_status" id="w9_status" tabindex="9" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="no"  {{ (!empty($vendor->w9_status) && $vendor->w9_status == 'no') ? 'selected' : '' }}>No</option>
                            <option value="yes" {{ (!empty($vendor->w9_status) && $vendor->w9_status == 'yes') ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 d-none" id="w9_upload_wrapper">
                    <div class="form-group">
                        <label>Upload W-9 Form</label>
                        <input type="file"
                            name="w9_files[]"
                            id="w9_files"
                            class="form-control"
                            multiple
                            accept=".pdf,.jpg,.jpeg,.png,.webp">

                        <small class="text-muted">
                            You can upload multiple files (PDF / Image)
                        </small>

                        {{-- Existing uploaded files (EDIT mode) --}}
                        @if(!empty($vendor->w9_files))
                            <div class="mt-3" id="existing_w9_files">

                                <table class="table table-bordered table-sm">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width:60px;">SL No</th>
                                            <th>File</th>
                                            <th style="width:80px;" class="text-center">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(json_decode($vendor->w9_files) as $index => $file)
                                            <tr class="w9-file-item" data-file="{{ $file }}">
                                                <td>{{ $index + 1 }}</td>

                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-info mb-1 w9-view-btn" data-file="{{ asset('storage/vendor_w9/'.$file) }}"> <i class="fas fa-file"></i> {{ $file }} </a>
                                                </td>

                                                <td class="text-center">
                                                    <span class="badge badge-danger delete-w9-file"
                                                        style="cursor:pointer;">
                                                        ✕
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <input type="hidden" name="deleted_w9_files" id="deleted_w9_files">
                            </div>
                        @endif

                    </div>
                </div>
                <div class="col-md-12 mt-3 d-none" id="w9_preview_wrapper">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                W-9 Preview
                            </h5>
                            <button type="button"
                                    class="close"
                                    onclick="$('#w9_preview_wrapper').addClass('d-none')">
                                &times;
                            </button>
                        </div>
                        <div class="card-body" id="w9_preview_body">
                            {{-- file loads here --}}
                        </div>
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
@include('partials.delete-modal')
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
<script>
$(function () {

    function toggleW9Upload() {
        if ($('#w9_status').val() === 'yes') {
            $('#w9_upload_wrapper').removeClass('d-none');
        } else {
            $('#w9_upload_wrapper').addClass('d-none');
            $('#w9_files').val('');
        }
    }

    // On page load (EDIT mode)
    toggleW9Upload();

    // On change
    $('#w9_status').on('change', function () {
        toggleW9Upload();
    });

});
</script>
<script>
$(document).on('click', '.view-w9-file', function () {

    const fileUrl = $(this).data('file');
    const isPdf = fileUrl.toLowerCase().endsWith('.pdf');

    let previewHtml = '';

    if (isPdf) {
        previewHtml = `
            <iframe src="${fileUrl}"
                    width="100%"
                    height="500"
                    style="border:1px solid #ccc;">
            </iframe>
        `;
    } else {
        previewHtml = `
            <img src="${fileUrl}"
                 class="img-fluid"
                 style="border:1px solid #ccc;">
        `;
    }

    $('#w9_preview_body').html(previewHtml);
    $('#w9_preview_wrapper').removeClass('d-none');

    // Smooth scroll to preview
    $('html, body').animate({
        scrollTop: $('#w9_preview_wrapper').offset().top - 80
    }, 300);
});
</script>
<script>
let deletedW9Files = [];
let w9RowToDelete = null;
let w9FileToDelete = null;

// When delete icon is clicked
$(document).on('click', '.delete-w9-file', function () {

    w9RowToDelete = $(this).closest('.w9-file-item');
    w9FileToDelete = w9RowToDelete.data('file');

    // Open modal
    $('#delete-confirmation-modal').modal('show');
});

// When YES is clicked in modal
$('#confirm-delete-btn').on('click', function (e) {
    e.preventDefault();

    if (!w9FileToDelete || !w9RowToDelete) return;

    // Store deleted file
    deletedW9Files.push(w9FileToDelete);

    // Update hidden input
    $('#deleted_w9_files').val(JSON.stringify(deletedW9Files));

    // Remove row from UI
    w9RowToDelete.remove();

    // Reset temp variables
    w9RowToDelete = null;
    w9FileToDelete = null;

    // Close modal
    $('#delete-confirmation-modal').modal('hide');
});
</script>
@endsection