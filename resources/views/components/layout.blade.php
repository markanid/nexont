<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>NexonT</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <link rel="stylesheet" href="{{asset('admin-assets/plugins/daterangepicker/daterangepicker.css')}}">

  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('admin-assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">

  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{asset('admin-assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">

  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('admin-assets/dist/css/adminlte.min.css')}}">
  @yield('styles')

  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css" />

  <!-- Toaster -->
  <link rel="stylesheet" href="{{ asset('admin-assets/plugins/toastr/toastr.min.css') }}">

    <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('admin-assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('admin-assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('admin-assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">

  <link rel="shortcut icon" type="image/x-icon" href="{{asset('admin-assets/dist/img/favicon.png')}}">

  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('admin-assets/plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('admin-assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
   

   <style>
    .dataTables_filter,.dataTables_paginate {
      float:right;
    }
    #example1_paginate,#example1_filter{
      float:right;
    }
  </style>
</head>
{{-- <body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed layout-navbar-fixed"> --}}
<body class="sidebar-mini layout-navbar-fixed layout-fixed layout-footer-fixed dark-mode">
<div class="wrapper">

  <!-- Preloader -->
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
          <img src="{{ asset('uploads/avatar.png') }}" class="img-sm img-circle" alt="User Image" style="margin-top: -4px;margin-right: -15px;">
          
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
          <!-- User image -->
          <li class="user-header bg-user">
            <img src="{{ asset('storage/user_logos/'.Auth::user()->avatar) }}" class="img-circle elevation-2" alt="User Image">

            <p>
              {{ Auth::user()->name }} - {{ Auth::user()->role }}
            </p>
          </li>
          
          <!-- Menu Footer-->
          <li class="user-footer">
            <a href="{{route('users.showprofile',Auth::user()->id)}}" class="btn btn-default btn-flat">Profile</a>
            <a href="{{route('logout')}}" class="btn btn-default btn-flat float-right">Log out</a>
          </li>
        </ul>
      </li>
      
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt" style="font-size: medium;"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  @include('components.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
     @yield('content-header')

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @yield('body')
        
      </div>
    </section>
     
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer text-sm">
    <strong>Powered by <a href="https://apexsoftlabs.com">Apex Soft Labs</a>.</strong>
    <div class="float-right"><b>Version</b> 2.0</div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('admin-assets/plugins/jquery/jquery.min.js')}}"></script>

<!-- jQuery UI 1.11.4 -->
<script src="{{asset('admin-assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>

<!-- Bootstrap 4 -->
<script src="{{asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<!-- ChartJS -->
<script src="{{asset('admin-assets/plugins/chart.js/Chart.min.js')}}"></script>

<!-- jQuery Knob Chart -->
<script src="{{asset('admin-assets/plugins/jquery-knob/jquery.knob.min.js')}}"></script>

<!-- InputMask -->
<script src="{{asset('admin-assets/plugins/inputmask/jquery.inputmask.min.js')}}"></script>

<!-- Moment.js -->
<script src="{{asset('admin-assets/plugins/moment/moment.min.js')}}"></script>

<!-- daterangepicker -->
<script src="{{asset('admin-assets/plugins/daterangepicker/daterangepicker.js')}}"></script>

<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('admin-assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>

<!-- Summernote -->
<script src="{{asset('admin-assets/plugins/summernote/summernote-bs4.min.js')}}"></script>

<!-- overlayScrollbars -->
<script src="{{asset('admin-assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>

<!-- AdminLTE App -->
<script src="{{asset('admin-assets/dist/js/adminlte.js')}}"></script>

<!-- Datatable plugins -->
<script src="{{asset('admin-assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('admin-assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('admin-assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('admin-assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('admin-assets/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('admin-assets/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('admin-assets/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('admin-assets/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('admin-assets/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('admin-assets/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

<!-- bs-custom-file-input -->
<script src="{{asset('admin-assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>

<!-- SweetAlert2 again (optional if you want the separate local version replaced too) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Toaster --}}
<script src="{{ asset('admin-assets/plugins/toastr/toastr.min.js') }}"></script>

<!-- jQuery Validate -->
<script src="{{asset('admin-assets/dist/js/jquery.validate.min.js')}}"></script>

<!-- Select2 -->
<script src="{{asset('admin-assets/plugins/select2/js/select2.full.min.js')}}"></script>

@yield('scripts')
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    //Date and time picker
    $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });
    $('#reservationdate').datetimepicker({
        format: 'DD/MM/YYYY'
    });    
  });
</script>
<script>
    // Apply global setting to disable ordering
    $.extend(true, $.fn.dataTable.defaults, {
        ordering: false
    });
</script>
<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "timeOut": "4000",
        "positionClass": "toast-top-right"
    };
</script>
@stack('scripts')
</body>
</html>
