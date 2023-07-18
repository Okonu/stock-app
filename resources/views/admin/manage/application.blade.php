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
            <div class="col-xl-12">

                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Partner Applications</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example3" class="display min-w850">
                                        <thead>
                                        <tr>
                                            <th>Partner Name</th>
                                            <th>Date Applied</th>
                                            <th>Status</th>
                                            <th>View Details</th>
                                            <th>Take Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($Applications as $application)
                                    <tr>
                                        <td>{{ $application->user->name }}</td>
                                        <td>{{ $application->created_at }}</td>
                                        <td>
                                            <span class="badge {{ $application->user->registration_status == 0 ? 'badge-danger' : 'badge-success' }}">
                                                {{ $application->user->registration_status == 0 ? 'Pending' : 'Approved' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('manage.profile', ['id' => $application->user->id]) }}" class="btn btn-primary btn-sm shadow text-center">
                                                    Details
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="#" class="btn btn-danger btn-sm shadow sharp"><i class="fa fa-trash"></i> Delete</a>
                                            </div>
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
