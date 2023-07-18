@extends('admin.layouts.app')

@section('content')
<!-- **********************************
    Content body start
*********************************** -->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <!-- Add Order -->
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="clearfix">
                    <div class="card card-bx author-profile m-b30">
                        <div class="card-body">
                            <div class="p-5">
                                <div class="author-profile">
                                    <form class="profile-form" id="updateProfileForm" action="{{ route('adminprofile.updatepic') }}" method="POST" enctype="multipart/form-data">
                                      @csrf
                                      <!-- Add the profile picture section -->
                                      <div class="author-media">
                                        <img id="profilePicturePreview" src="<?php echo url('storage/app/public/profile_picture/' . auth()->user()->company->profile_pic); ?>" alt="">
                                        <div class="upload-link" title="" data-bs-toggle="tooltip" data-placement="right" data-original-title="update">
                                          <input type="file" name="profile_picture" id="profile-picture-input" class="update-file">
                                          <i class="fa fa-camera"></i>
                                        </div>
                                      </div>
                                      <!-- Rest of the form fields -->
                                      <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">UPDATE</button>
                                      </div>
                                    </form>
                                    <div class="author-info">
                                        <h6 class="title">{{ auth()->user()->name }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8">
                <div class="card  card-bx m-b30">
                    <div class="card-header">
                        <h6 class="title">Business Details</h6>
                    </div>
                   <form class="profile-form" action="{{ route('admin.profile')}}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 m-b30">
                                    <label class="form-label">Business Name</label>
                                    <input type="text" name="company_name" class="form-control" value="{{ auth()->user()->company->name }}">
                                </div>
                                <div class="col-sm-6 m-b30">
                                    <label class="form-label">Business Email</label>
                                    <input type="email" name="business_email" class="form-control" value="{{ auth()->user()->company->business_email }}">
                                </div>
                                <div class="col-sm-6 m-b30">
                                    <label class="form-label">Business Phone Number</label>
                                    <input type="telephone" name="telephone" class="form-control" value="{{ auth()->user()->company->telephone }}">
                                </div>
                                <div class="col-sm-6 m-b30">
							    <label class="form-label">Business Location</label>
							    <select name="location" class="form-control form-control-user @error('location') is-invalid @enderror">
							        <option value="" selected disabled>Choose a Location</option>
							        @foreach($locations as $location)
							            <option value="{{ $location->id }}" {{ $company->location_id == $location->id ? 'selected' : '' }}>
							                {{ $location->name }}
							            </option>
							        @endforeach
							    </select>
							    @error('location')
							        <span class="invalid-feedback">{{ $message }}</span>
							    @enderror
							</div>
                                <div class="col-sm-6 m-b30">
                                    <label class="form-label">Business Identification Number</label>
                                    <input name="business_identification_number" type="text" class="form-control form-control-user @error('business_identification_number') is-invalid @enderror" id="exampleInputBusinessId" placeholder="Business Identification Number" value="{{ auth()->user()->company->business_identification_number }}">
                                    @error('business_identification_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                               <div class="col-sm-6 m-b30">
							    <label class="form-label">Business Type</label>
							    <select name="company_type" class="form-control form-control-user @error('company_type') is-invalid @enderror">
							        <option value="" selected disabled>Company Type</option>
							        @foreach($companyTypes as $companyType)
							            <option value="{{ $companyType->id }}" {{ $company->company_type_id == $companyType->id ? 'selected' : '' }}>
							                {{$companyType->name}}
							            </option>
							        @endforeach
							    </select>
							    @error('company_type')
							        <span class="invalid-feedback">{{$message}}</span>
							    @enderror
							</div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">UPDATE</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- **********************************
    Content body end
*********************************** -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  $(document).ready(function() {
    // Handle file input change event
    $('#profile-picture-input').change(function(event) {
      var input = event.target;
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $('#profilePicturePreview').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    });

    // Handle form submission
    $('#updateProfileForm').submit(function(event) {
      event.preventDefault();

      var formData = new FormData(this);

      $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(response) {
          // Update the profile picture preview
          $('#profilePicturePreview').attr('src', '{{ asset('public/storage/profile_pictures') }}/' + response.filename);

          // Show success message
          alert(response.success);
        },
        error: function(xhr, status, error) {
          // Show error message
          alert(xhr.responseJSON.error);
        }
      });
    });
  });
</script>
@endsection
