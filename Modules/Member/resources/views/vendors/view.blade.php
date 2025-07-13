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
                                <td>GST</td>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection