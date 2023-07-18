<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Fursa - Login</title>
  <!-- Custom fonts for this template-->
  <link href="{{ asset('front/Authcssjs/all.min.css') }}" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
 
  <!-- Custom styles for this template-->
  <link href="{{ asset('front/Authcssjs/sb-admin-2.min.css') }}" rel="stylesheet">
  <style>
    body {
      background-color: #f5fefd;
    }
    .container {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="col-xl-6 col-lg-7 col-md-9">
      <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
          <div class="p-5">
            <div class="text-center">
              <img src="{{ asset('front/images/fursa.png') }}" alt="Logo" height="100" class="mb-4">
              <hr>
              <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
            </div>
            <form action="{{ route('login.action') }}" method="POST" class="user">
              @csrf
              @if ($errors->any())
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
              @endif
              <div class="form-group">
                <input name="email" type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address...">
              </div>
              <div class="form-group">
                <input name="password" type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
              </div>
              <div class="form-group">
                <div class="custom-control custom-checkbox small">
                  <input name="remember" type="checkbox" class="custom-control-input" id="customCheck">
                  <label class="custom-control-label" for="customCheck">Remember Me</label>
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-block btn-user">Login</button>
            </form>
            <hr>
            <div class="text-center">
              <a class="small" href="{{ route('register') }}">Create an Account!</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('front/Authcssjs/jquery.min.js') }}"></script>
  <script src="{{ asset('front/Authcssjs/bootstrap.bundle.min.js') }}"></script>
  <!-- Core plugin JavaScript-->
  <script src="{{ asset('front/Authcssjs/jquery.easing.min.js') }}"></script>
  <!-- Custom scripts for all pages-->
  <script src="{{ asset('front/Authcssjs/sb-admin-2.min.js') }}"></script>
</body>
</html>
