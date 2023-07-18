@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top:100px">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Pending Approval</div>

                    <div class="card-body">
                        <p>Your registration is pending approval or has been rejected.</p>
                        <p>Please wait for the administrator to approve your account.</p>
                        <p>If you believe this is an error, please contact our support team.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
