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
        <h3 class="card-title"><i class="far fa-building"></i> {{$page_title}}</h3>
        <div class="card-tools">
            <a class="btn btn-primary btn-sm btn-flat" href="{{route('companies.create')}}"><i class="fas fa-plus-circle"></i> Create</a> &nbsp;
            <a class="btn btn-dark btn-sm btn-flat float-right" href="{{route('companies.index')}}"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
        </div>
    </div>
    <div class="card-body">
        
        <div class="table-responsive">
            <table class="table table-bordered" style="margin-bottom: 10px;">
                <tbody>
                    <tr>
                        <td style="background-color:#096ca5; color:#fff;">Company Name</td>
                        <td><b style="color:#096ca5;">{{ $company->name}}</b>
                        </td>
                    </tr>
                </tbody>
            </table>
       </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td rowspan="2">
                            <div>
                                @if(!empty($logo))
                                    <p><img src="{{asset('storage/company_logos/'.$logo_path)}}" alt="Company Photo" style="width: 200px; height: 100px;"></p>
                                @else
                                    <p><img src="{{asset('uploads/avatar.png')}}" alt="Company Photo" style="width: 200px; height: 50px;"></p>
                                @endif 
                            </div>
                        </td>
                        <td>
                            <span>Phone :</span>
                            <label>{{ $company->phone }}</label>
                        </td>
                        <td>
                            <span>Email :</span>
                            <label>{{ $company->email }}</label>
                        </td>
                        <td>
                            <span>Website :</span>
                            <label>{{ $company->website }}</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>Address :</span>
                            <label>{{ $company->address }}</label>
                        </td>
                        <td>
                            <span>Type :</span>
                            <label>{{ $company->type === 'client' ? 'Client' : 'Company' }}</label>
                        </td>
                        @if($company->type === 'client')
                        <td>
                            <span>Client ID :</span>
                            <label>{{ $company->client_id }}</label>
                        </td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
       
    </div>
   
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif
        @if(session('info'))
            Toast.fire({
                icon: 'info',
                title: '{{ session('info') }}'
            });
        @endif
    });
</script>
@endsection