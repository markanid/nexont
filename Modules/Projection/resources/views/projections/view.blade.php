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
         @if(in_array(auth()->user()->role, ['Admin', 'PMO']))
            <a href="{{ route('projections.edit', $projection->id) }}" class="btn btn-success btn-sm btn-flat"><i class="fas fa-edit"></i> Edit</a>
         @endif
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
               @if(in_array(auth()->user()->role, ['Admin', 'PMO']))
                  <div class="mt-3">
                     <h5 class="mb-2"><strong>Projection Summary (User-wise)</strong></h5>

                     <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                           <thead>
                              <tr>
                                 <th>#</th>
                                 <th>User</th>
                                 <th>Total Projection</th>
                                 <th>View Details</th>
                              </tr>
                           </thead>
                           <tbody>
                              @forelse($projection_summary as $summary)
                                 <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $summary->creator->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($summary->total_projection, 2) }}</td>
                                    <td>
                                       <a class="btn btn-app view-user-projects" data-user="{{ $summary->created_by }}"><i class="far fa-eye"></i></a>
                                    </td>
                                 </tr>
                              @empty
                                 <tr>
                                    <td colspan="4" class="text-center">No data available</td>
                                 </tr>
                              @endforelse
                           </tbody>
                        </table>
                     </div>
                  </div>
               @endif
            </div>
         </div>
         <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
            <a class="btn btn-primary btn-sm btn-flat float-right" href="{{ route('runningprojects.adddetails', ['projection_id' => $projection->id]) }}"><i class="fas fa-plus-circle"></i> Create</a>
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
                              <th>Options</th>
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
                              <td>
                                 <a class="btn btn-app" href="{{route('runningprojects.editdetails', $running_project->id)}}"><i class="far fa-edit"></i></a>
                                 <a href="#" class="btn btn-app-delete delete-btn" data-url="{{ route('runningprojects.deletedetails', ['id' => $running_project->id]) }}"><i class="far fa-trash-alt"></i></a>
                              </td>
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
@push('scripts')
<script>
   document.addEventListener('DOMContentLoaded', function () {
       const hash = window.location.hash;
       if (!hash) return;
   
       const triggerEl = document.querySelector('a[href="' + hash + '"]');
       if (triggerEl) {
           const tab = new bootstrap.Tab(triggerEl);
           tab.show();
       }
   });
</script>
<script>
$(document).ready(function() {
    $('.view-user-projects').on('click', function() {
        let userId = $(this).data('user');
        let projectionId = {{ $projection->id }};

        $.ajax({
            url: "{{ route('projections.runningprojects.filter', $projection->id) }}",
            data: { created_by: userId },
            success: function(res) {
                let tbody = '';
                let i = 1;

                if(res.length === 0) {
                    tbody = `<tr>
                        <td colspan="12" class="text-center">No running projects found</td>
                    </tr>`;
                } else {
                    res.forEach(function(row) {
                        tbody += `
                        <tr>
                            <td>${i++}</td>
                            <td>${row.project.project_code}</td>
                            <td>${row.project.project_name}</td>
                            <td>${row.project.client.name}</td>
                            <td>${row.projection_value}</td>
                            <td>${row.type}</td>
                            <td>${row.billing_desc ?? ''}</td>
                            <td>${row.status}</td>
                            <td>${row.remarks ?? ''}</td>
                            <td>${row.project.po ?? ''}</td>
                            <td>${row.invoice_details ?? ''}</td>
                            <td>
                                <a class="btn btn-app" href="/runningprojects/editdetails/${row.id}">
                                    <i class="far fa-edit"></i>
                                </a>
                            </td>
                        </tr>`;
                    });
                }

                $('#running_projects_table tbody').html(tbody);

                // Optional: Switch to Running Projects tab
                $('#custom-tabs-one-profile-tab').tab('show');
            }
        });
    });
});
</script>

@endpush
@endsection
@include('partials.delete-modal')

@section('scripts')

@include('partials.delete-modal-script')
@include('partials.common-index-script', ['tableId' => 'running_projects_table'])
@endsection