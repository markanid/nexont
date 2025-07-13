<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>NexonT | Registration Page</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" type="text/css" href="{{asset('admin-assets/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('admin-assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" type="text/css" href="{{asset('admin-assets/dist/css/adminlte.min.css')}}">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
  <a href="#"><b>NexonT</b> Engineering</a>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Register a new user</p>

      <form action="{{route('auth.registerProcess')}}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" tabindex="1" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          @error('email')
            <p class="invalid-feedback">{{$message}}</p>
         @enderror
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text" style="cursor:pointer;">
              <span class="fas fa-eye" id="eyeIcon1"></span>
            </div>
          </div>
          @error('password')
            <p class="invalid-feedback">{{$message}}</p>
          @enderror
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password_confirmation" id="password_confirmation" tabindex="3" class="form-control" placeholder="Retype password">
          <div class="input-group-append">
            <div class="input-group-text" style="cursor:pointer;">
              <span class="fas fa-eye" id="eyeIcon2"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <select name="role" id="role" class="form-control" tabindex="4">
            <option value="">-- Select Role --</option>
            @if (!$hasUsers)
              @if (!$hasAdminUser)
                <option value="Admin">Admin</option>
              @endif
            @else
              @if (!$hasAdminUser)
                <option value="Admin">Admin</option>
              @endif
              <option value="Project Manager">Project Manager</option>
              <option value="PMO">PMO</option>
              <option value="Sales Manager">Sales Manager</option>
              <option value="Accountant">Accountant</option>
              @if ($hasClientCompany)
                <option value="Client">Client</option>
              @endif
            @endif
          </select>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user-tag"></span>
            </div>
          </div>
          @error('role')
            <p class="invalid-feedback">{{ $message }}</p>
          @enderror
        </div>

        <div class="input-group mb-3" id="nameTextGroup" style="display:none;">
          <input type="text" name="name" class="form-control" placeholder="Full name" tabindex="5">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
          @error('name')
            <p class="invalid-feedback">{{ $message }}</p>
          @enderror
        </div>

        <div class="input-group mb-3" id="nameDropdownGroup" style="display:none;">
          <select name="name" class="form-control" tabindex="5">
            <option value="">-- Select Employee --</option>
            {{-- @foreach($employees as $employee)
              <option value="{{ $employee->name }}">{{ $employee->name }}</option>
            @endforeach --}}
          </select>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
          @error('employee_id')
            <p class="invalid-feedback">{{ $message }}</p>
          @enderror
        </div>

        <div class="input-group mb-3" id="clientCompanyBox" style="display:none;">
          <select name="company_id" id="company_id" class="form-control" tabindex="6">
            <option value="">-- Select Client Company --</option>
            @foreach($clientCompanies as $company)
              <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
          </select>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-building"></span>
            </div>
          </div>
          @error('company_id')
            <p class="invalid-feedback">{{ $message }}</p>
          @enderror
        </div>      
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" tabindex="6" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" tabindex="7" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <div class="social-auth-links text-center">
        <p>- OR -</p>
      </div>

      <a href="{{route('auth.login')}}" class="text-center">I already have an account</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="{{asset('admin-assets/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('admin-assets/dist/js/adminlte.min.js')}}"></script>
<script>
  function togglePasswordVisibility(passwordFieldId, iconId) {
    const passwordInput = document.getElementById(passwordFieldId);
    const eyeIcon = document.getElementById(iconId);
    
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      eyeIcon.classList.remove('fa-eye');
      eyeIcon.classList.add('fa-eye-slash');
    } else {
      passwordInput.type = 'password';
      eyeIcon.classList.remove('fa-eye-slash');
      eyeIcon.classList.add('fa-eye');
    }
  }

  document.getElementById('eyeIcon1').addEventListener('click', function () {
    togglePasswordVisibility('password', 'eyeIcon1');
  });

  document.getElementById('eyeIcon2').addEventListener('click', function () {
    togglePasswordVisibility('password_confirmation', 'eyeIcon2');
  });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const clientCompanyBox = document.getElementById('clientCompanyBox');
    const nameTextGroup = document.getElementById('nameTextGroup');
    const nameDropdownGroup = document.getElementById('nameDropdownGroup');

    function toggleClientCompanyBox() {
        const selectedRole = roleSelect.value;
        if (selectedRole === 'Client') {
            clientCompanyBox.style.display = 'flex'; // or 'block'
        } else {
            clientCompanyBox.style.display = 'none';
        }
    }

    function toggleNameFields() {
        const selectedRole = roleSelect.value;
        if (selectedRole === 'Admin' || selectedRole === 'Client') {
            nameTextGroup.style.display = 'flex';    // Show text input
            nameDropdownGroup.style.display = 'none'; // Hide dropdown
        } else if (selectedRole) {
            nameTextGroup.style.display = 'none';    // Hide text input
            nameDropdownGroup.style.display = 'flex';  // Show dropdown
        } else {
            // No role selected
            nameTextGroup.style.display = 'none';
            nameDropdownGroup.style.display = 'none';
        }
    }

    // Initial load
    toggleClientCompanyBox();
    toggleNameFields();

    // On change
    roleSelect.addEventListener('change', function () {
        toggleClientCompanyBox();
        toggleNameFields();
    });
});
</script>
</body>
</html>
