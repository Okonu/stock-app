@extends('layouts.app')

@section('content')
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <!-- Add Order -->
            <div class="row justify-content-center">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="profile-tab">
                                <div class="custom-tab-1">
                                    <!-- Personal Information -->
                                     <div class="profile-personal-info">
                                        <h4 class="text-primary mb-4">Personal Information</h4>
                                        <div class="row mb-4 mb-sm-2">
                                            <div class="col-sm-3">
                                                <h5 class="f-w-500">Name <span class="pull-right d-none d-sm-block">:</span>
                                                </h5>
                                            </div>
                                            <div class="col-sm-9">
                                                <span>{{ $seeker->first_name }} {{ $seeker->last_name }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-4 mb-sm-2">
                                            <div class="col-sm-3">
                                                <h5 class="f-w-500">Email <span class="pull-right d-none d-sm-block">:</span>
                                                </h5>
                                            </div>
                                            <div class="col-sm-9">
                                                <span>{{ $seeker->email }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-4 mb-sm-2">
                                            <div class="col-sm-3">
                                                <h5 class="f-w-500">Phone <span class="pull-right d-none d-sm-block">:</span></h5>
                                            </div>
                                            <div class="col-sm-9">
                                                <span>{{ $seeker->phone }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-4 mb-sm-2">
                                            <div class="col-sm-3">
                                                <h5 class="f-w-500">ID No <span class="pull-right d-none d-sm-block">:</span></h5>
                                            </div>
                                            <div class="col-sm-9">
                                                <span>{{ $profile->id_number}}</span>
                                            </div>
                                        </div>
                                        <!-- Other fields here -->
                                    </div>

                                    <!-- Education Details -->
                                   <div class="profile-personal-info">
                                        <h4 class="text-primary mb-4">Education Details</h4>
                                        <div class="row mb-4 mb-sm-2">
                                            <div class="col-sm-3">
                                                <h5 class="f-w-500">School <span class="pull-right d-none d-sm-block">:</span></h5>
                                            </div>
                                            <div class="col-sm-9">
                                                <span>{{ $profile->school}}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-4 mb-sm-2">
                                            <div class="col-sm-3">
                                                <h5 class="f-w-500">Year Of Completion <span class="pull-right d-none d-sm-block">:</span></h5>
                                            </div>
                                            <div class="col-sm-9">
                                                <span>{{ $profile->year_of_completion}}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-4 mb-sm-2">
                                            <div class="col-sm-3">
                                                <h5 class="f-w-500">Achievement <span class="pull-right d-none d-sm-block">:</span></h5>
                                            </div>
                                            <div class="col-sm-9">
                                                <span>{{ $level->name}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    <!-- Interests -->
                                    <div class="profile-interests mb-5">
                                        <h4 class="text-primary mb-2">Interests</h4>
                                        @foreach ($interests as $interest)
                                            <a href="javascript:void(0);" class="btn btn-primary light btn-xs mb-1">{{ $interest }}</a>
                                        @endforeach
                                    </div>
                                    
                                    <!-- Skills -->
                                    <div class="profile-skills mb-5">
                                        <h4 class="text-primary mb-2">Skills</h4>
                                        @foreach ($skills as $skill)
                                            <a href="javascript:void(0);" class="btn btn-primary light btn-xs mb-1">{{ $skill }}</a>
                                        @endforeach
                                    </div>
                                    
                                    <!-- Files -->
                                    <!--<div class="profile-skills mb-5">-->
                                    <!--    <h4 class="text-primary mb-2">Files</h4>-->
                                    <!--    <a href="javascript:void(0);" class="btn btn-primary light btn-xs mb-1">{{ $profile->resume}}</a>-->
                                    <!--</div>-->
                                    <div class="profile-skills mb-5">
                                        <h4 class="text-primary mb-2">Files</h4>
                                        <a href="{{ route('resume.show', ['filename' => $profile->resume]) }}" target="_blank" class="btn btn-primary light btn-xs mb-1">View Resume</a>
                                    </div>

                                    <!-- Application Status -->
                                   
                                        <!--<div class="text-center">-->
                                        <!--    <button class="btn btn-success mr-3" onclick="acceptApplication()">Accept</button>-->
                                        <!--    <button class="btn btn-danger" onclick="rejectApplication()">Reject</button>-->
                                        <!--</div>-->
                                     <div class="text-center">
                                        @if ($status === 'in review')
                                             <form action="{{ route('JobApplicationupdate.status', ['jobApplicationId' => $applicationId]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" name="status" value="1" class="btn btn-success mr-3">Accept</button>
                                                <button type="submit" name="status" value="0" class="btn btn-danger">Reject</button>
                                            </form>
                                        @elseif ($status === 'accepted')
                                            <p>Job Approved</p>
                                        @elseif ($status === 'rejected')
                                            <p>Job Rejected</p>
                                        @endif
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function acceptApplication() {
            // Add your accept application logic here
            alert('Application accepted');
        }

        function rejectApplication() {
            // Add your reject application logic here
            alert('Application rejected');
        }
    </script>
@endsection
