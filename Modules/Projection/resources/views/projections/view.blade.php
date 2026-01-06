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


            <div class="card card-primary card-tabs">
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true"><i class="far fa-file-alt"></i> Basic Details</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Running Projects</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Change Order</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Connection Design Value</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
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
                  <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                    <a class="btn btn-primary btn-sm btn-flat float-right" href="{{ route('runningprojects.adddetails', $projection->id) }}"><i class="fas fa-plus-circle"></i> Create</a>
                    <br>  <br>
                    <div class="card">
                      <div class="card-body">
                          <div class="table-responsive">
                              <table id="running_projects_table" class="table table-bordered table-striped">
                                  <thead>
                                      <tr>
                                          <th>#</th>
                                          <th>Nexont Project ID</th>
                                          <th>Project Name</th>
                                          <th>Client</th>
                                          <th>Projection</th>
                                          <th>Work Type</th>
                                          <th>Billing Description</th>
                                          <th>Work Status</th>
                                          <th>Remarks</th>
                                          <th>PO Details</th>
                                          <th>Invoice Details</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($running_projects as $running_project)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $running_project->project->project_code }}</td>
                                            <td>{{ $running_project->project->project_name }}</td>
                                            <td>{{ $running_project->project->client->name}}</td>
                                            <td>{{ $running_project->projection_value }}</td>
                                            <td>{{ $running_project->type }}</td>
                                            <td>{{ $running_project->billing_desc }}</td>
                                            <td>{{ $running_project->status }}</td>
                                            <td>{{ $running_project->remarks }}</td>
                                            <td>{{ $running_project->project->po}}</td>
                                            <td>{{ $running_project->invoice_details }}</td>
                                        </tr>
                                    @endforeach
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                     Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat augue id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus tristique. Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare magna.
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                     Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet accumsan ex sit amet facilisis.
                  </div>
                </div>
              </div>
              <!-- /.card -->
            </div>
          

@endsection
