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
                    <li class="breadcrumb-item"><a href="{{ route('projections.index') }}">Employee</a></li>
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
        <h3 class="card-title"><i class="fas fa-user-tag"></i> {{ $page_title }}</h3>
        <div class="card-tools">
            <a href="{{ route('projections.edit', $projection->id) }}" class="btn btn-success btn-sm btn-flat"><i class="fas fa-edit"></i> Edit</a>
            <a href="{{ route('projections.index') }}" class="btn btn-dark btn-sm btn-flat"><i class="fas fa-arrow-alt-circle-left"></i> Back</a>
        </div>
    </div>
</div>

<div class="card card-navy">
    <div class="card-header">
        <h3 class="card-title"><i class="far fa-file-alt"></i> Basic Details</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td style="height: 69px;">Projection</td>
                        <td style="color: #007bff;">{{ $projection->month.'-'.$projection->year }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
