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
      @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if (session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
      @endif
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
          <input type="text" name="name" class="form-control" placeholder="Full name" tabindex="4">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
          @error('name')
            <p class="invalid-feedback">{{ $message }}</p>
          @enderror
        </div>
 
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" tabindex="5" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" tabindex="6" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <div class="social-auth-links text-center">
        <p>- OR -</p>
      </div>
      <div class="text-center mt-3">
        <p class="mb-2">Already have an account?</p>
        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-block">
          <i class="fas fa-sign-in-alt"></i> Sign In Here
        </a>
      </div>
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
</body>
</html>
