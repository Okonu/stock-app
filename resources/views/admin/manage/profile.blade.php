@extends('admin.layouts.app')

@section('content')
<!--**********************************
        Content body start
    ***********************************-->
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
                                    <div class="author-media">
                                        <img id="profilePicturePreview" src="{{ asset('images/profile.jpg') }}" alt="">
                                    </div>
                                    <div class="author-info">
                                        <h6 class="title">{{ $partner->company->name }}</h6>
                                        <!-- Display other information about the partner -->
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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 m-b30">
                                <label class="form-label"><strong>Business Name</strong></label>
                                <p>{{ $partner->company->name }}</p>
                            </div>
                            <div class="col-sm-6 m-b30">
                                <label class="form-label"><strong>Business Email</strong></label>
                                <p>{{ $partner->company->business_email }}</p>
                            </div>
                            <div class="col-sm-6 m-b30">
                                <label class="form-label"><strong>Business Phone Number</strong></label>
                                <p>{{ $partner->company->telephone }}</p>
                            </div>
                            <div class="col-sm-6 m-b30">
                                <label class="form-label"><strong>Business Location</strong></label>
                                <p>{{ $partner->company->location->name }}</p>
                            </div>
                            <div class="col-sm-6 m-b30">
                                <label class="form-label"><strong>Business Identification Number</strong></label>
                                <p>{{ $partner->company->business_identification_number }}</p>
                            </div>
                            <div class="col-sm-6 m-b30">
                                <label class="form-label"><strong>Business Type</strong></label>
                                <p>{{ $partner->company->companyType->name }}</p>
                            </div>
                        </div>
                    </div>
                 <div class="card-footer text-center">
                        <div class="row justify-content-center">
                            <!-- Include the CSRF token -->
                            <div class="form-group">
                               <form id="approveUserForm" method="POST">
                                @csrf
                                <label for="approve">Approve as:</label>
                                <select name="approve" id="approve">
                                    <option value="partner">Partner</option>
                                    <option value="admin">Admin</option>
                                </select>
                                <div id="approveButtons" style="margin-top: 30px;">
                                    @if ($partner->level == 'pending')
                                        <button type="button" name="action" value="approve" class="btn btn-success">Approve</button>
                                        <button type="button" name="action" value="reject" class="btn btn-success">Reject</button>
                                    @else
                                        <button type="button" name="action" value="deactivate" style="background-color: red; color: white;">Deactivate</button>
                                    @endif
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Assign form action based on the button clicked
$(document).ready(function() {
    $('button[name="action"]').click(function() {
        var action = $(this).val();
        var form = $('#approveUserForm');
        var actionUrl;

        if (action === "approve") {
            actionUrl = '{{ route("admin.approveUser", ["userId" => $partner->id]) }}';
        } else if (action === "reject" || action === "deactivate") {
            actionUrl = '{{ route("admin.deactivateUser", ["userId" => $partner->id]) }}';
        }

        form.attr('action', actionUrl);
        form.submit();
    });
});
</script>


@endsection
