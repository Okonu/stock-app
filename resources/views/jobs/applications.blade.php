    @extends('layouts.app')
    @section('content')
    <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            <div class="container-fluid">
                <!-- Add Order -->
                <div class="row">
                    <div class="col-xl-12">
                        
                    <div class="row">
                    
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Job Applications</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                     <table id="example3" class="display min-w850">
                                        <thead>
                                            <tr>
                                                
                                                <th>Job Name</th>
                                                <th>Date Applied</th>
                                                <th>Name Of Applicant</th>
                                                <th>View Details</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                                @foreach ($jobApplications as $application)
                                                <tr>
                                                <td>{{ $application->job->title }}</td>
                                                <td>{{ $application->created_at }}</td>
                                                <td>{{ $application->seeker->first_name }} {{ $application->seeker->last_name }}</td>
                                                <td>
                                                    <a href="{{ route('jobs.profileaplicant', ['id' => $application->seeker->id, 'applicationId' => $application->id]) }}" class="btn btn-primary btn-xs shadow text-center w-100">
                                                    Details
                                                   </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    
                    
                </div>
                    </div>
                    <!--  -->
                </div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
        @endsection