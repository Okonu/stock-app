<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Fursa - Register</title>
  <!-- Custom fonts for this template-->
  <link href="{{ asset('front/Authcssjs/all.min.css') }}" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
 
  <!-- Custom styles for this template-->
  <link href="{{ asset('front/Authcssjs/sb-admin-2.min.css') }}" rel="stylesheet">
</head>
<body style="background-color: #f5fefd;">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 col-sm-10">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                    <img src="{{ asset('front/images/fursa.png') }}" alt="Logo" height="100" class="mb-4">
                    <hr>
                    <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                  </div>
                  <form action="{{ route('register.save') }}" method="POST" class="user">
                    @csrf
                    <div class="form-group">
                      <input name="name" type="text" class="form-control form-control-user @error('name') is-invalid @enderror" id="exampleInputName" placeholder="Name">
                      @error('name')
                          <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <input name="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" id="exampleInputEmail" placeholder="Email Address">
                      @error('email')
                          <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-6 mb-3 mb-sm-0">
                        <input name="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" id="exampleInputPassword" placeholder="Password">
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                      </div>
                      <div class="col-sm-6">
                        <input name="password_confirmation" type="password" class="form-control form-control-user @error('password_confirmation') is-invalid @enderror" id="exampleRepeatPassword" placeholder="Repeat Password">
                        @error('password_confirmation')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                      </div>
                    </div>
                    <!-- Company Information -->
                    <div class="form-group">
                      <input name="company_name" type="text" class="form-control form-control-user @error('company_name') is-invalid @enderror" id="exampleInputCompanyName" placeholder="Company Name">
                      @error('company_name')
                          <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                  <div class="form-group">
                    <select name="location" class="form-control  @error('location') is-invalid @enderror">
                        <option value="" selected disabled>Select Location</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                    @error('location')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                    <div class="form-group">
                      <select name="company_type" class="form-control  @error('company_type') is-invalid @enderror">
                        <option value="" selected disabled >Company Type</option>
                        @foreach ($companyTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                      </select>
                      @error('company_type')
                          <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <input name="business_email" type="email" class="form-control form-control-user @error('business_email') is-invalid @enderror" id="exampleInputBusinessEmail" placeholder="Business Email">
                      @error('business_email')
                          <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <input name="telephone" type="text" class="form-control form-control-user @error('telephone') is-invalid @enderror" id="exampleInputTelephone" placeholder="Telephone">
                      @error('telephone')
                          <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <input name="business_registration_files" type="file" class="form-control-file @error('business_registration_files') is-invalid @enderror" id="exampleInputRegistrationFiles">
                      @error('business_registration_files')
                          <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <input name="business_identification_number" type="text" class="form-control form-control-user @error('business_identification_number') is-invalid @enderror" id="exampleInputBusinessId" placeholder="Business Identification Number">
                      @error('business_identification_number')
                          <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">Register Account</button>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
                  </div>
                </div>
              </div>
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
