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
        <div class="card-tools">
            <a class="btn btn-success btn-sm btn-flat" href="{{route('vendors.edit', $vendor->id)}}"><i class="fas fa-edit"></i> Edit</a>
            <a class="btn btn-dark btn-sm btn-flat" href="{{route('vendors.index')}}"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card card-navy">
            <div class="card-header">
                <h3 class="card-title"><i class="far fa-file-alt"></i> Basic Details</h3>
            </div>
            <div class="card-body"> 
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td style="width: 120px;height: 69px;">Vendor Image</td>
                                <td>@if(!empty($vendor->logo))
                                        <p><img src="{{asset('storage/vendor_logos/'.$vendor->logo)}}" alt="Vendor Photo" style="width: 200px; height: 100px;"></p>
                                    @else
                                        <p><img src="{{asset('uploads/default_company_logo.png')}}" alt="Vendor Photo" style="width: 200px; height: 50px;"></p>
                                    @endif 
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 69px;">Vendor Name</td>
                                <td style="color: #007bff;">{{ $vendor->company_name }}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>{{ $vendor->company_phone }}</td>
                            </tr>
                            <tr>
                                <td style="height: 103px;">Address</td>
                                <td>{{ $vendor->address }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{ $vendor->email }}</td>
                            </tr>
                            <tr>
                                <td>EIN No.</td>
                                <td>{{ $vendor->gst_number }}</td>
                            </tr>
                            <tr>
                                <td>Website</td>
                                <td>{{ $vendor->website }}</td>
                            </tr>
                            <tr>
                                <td>Contact Person</td>
                                <td>{{ $vendor->rep_name }}</td>
                            </tr>
                            <tr>
                                <td>Contact No.</td>
                                <td>{{ $vendor->rep_phone }}</td>
                            </tr>
                            <tr>
                                <td>W-9 Files</td>
                                <td>
                                    @if($vendor->w9_status === 'yes' && !empty($vendor->w9_files))
                                        @foreach(json_decode($vendor->w9_files, true) as $file)
                                            @php
                                                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                            @endphp
                                            <a href="javascript:void(0);" 
                                            class="btn btn-sm btn-outline-info mb-1 w9-view-btn" 
                                            data-url="{{ asset('storage/vendor_w9/'.$file) }}" 
                                            data-ext="{{ $ext }}">
                                                <i class="fas fa-file"></i> {{ $file }}
                                            </a>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Not Provided</span>
                                    @endif

                                    <!-- Preview container -->
                                    <div id="w9-preview" style="display:none; margin-top:10px; border:1px solid #ccc; padding:10px;">
                                        <button type="button" class="btn btn-danger btn-sm mb-2" id="w9-close-btn">Close Preview</button>
                                        <div id="w9-preview-content" style="min-height:200px;"></div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const previewContainer = document.getElementById('w9-preview');
    const previewContent = document.getElementById('w9-preview-content');
    const closeBtn = document.getElementById('w9-close-btn');

    // Attach click event to all W-9 buttons
    document.querySelectorAll('.w9-view-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            const ext = this.dataset.ext.toLowerCase();

            previewContent.innerHTML = ''; // clear previous content

            if(['jpg','jpeg','png','webp'].includes(ext)) {
                let img = document.createElement('img');
                img.src = url;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '400px';
                img.classList.add('img-fluid');
                previewContent.appendChild(img);
            } else if(ext === 'pdf') {
                let embed = document.createElement('embed');
                embed.src = url;
                embed.type = 'application/pdf';
                embed.width = '100%';
                embed.height = '400px';
                previewContent.appendChild(embed);
            } else {
                let a = document.createElement('a');
                a.href = url;
                a.target = '_blank';
                a.classList.add('btn','btn-primary');
                a.innerText = 'Open File';
                previewContent.appendChild(a);
            }

            previewContainer.style.display = 'block';
            previewContainer.scrollIntoView({behavior: 'smooth'});
        });
    });

    // Close preview
    closeBtn.addEventListener('click', function() {
        previewContainer.style.display = 'none';
    });
});
</script>
@endsection