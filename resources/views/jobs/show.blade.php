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
                                <div class="d-flex align-items-center">
                                    <h4 class="card-title">Jobs Created</h4>
                                    <div class="flex-grow-1"></div>
                                    <div>
                                        <a href="{{ route('jobs.create.view') }}" class="btn btn-primary">Add Job</a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example3" class="display min-w850">
                                        <thead>
                                            <tr>
                                                <th>Job Name</th>
                                                <th>Job Description</th>
                                                <th>Skills and Qualifications</th>
                                                <th>No of Applicants Desired:</th>
                                                <th>Vacancies:</th>
                                                <th>Location</th>
                                                <th>Job Type</th>
                                                <th>Job Status</th>
                                                <th>Change Status</th>
                                                <th>Take Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                               @foreach ($jobs as $job)
                                            <tr>
                                                <td class="text-center">{{ $job->title }}</td>
                                                <td class="text-center">{{ \Illuminate\Support\Str::words($job->description, 3, '...') }}</td>
                                                <td class="text-center">
                                                    @foreach ($job->skills as $skill)
                                                    {{ $skill->skill }}
                                                    @if (!$loop->last)
                                                    ,
                                                    @endif
                                                    @endforeach
                                                </td>
                                                <td class="text-center">{{ $job->applicants }}</td>
                                                <td class="text-center">{{ $job->vacancies }}</td>
                                                <td class="text-center">{{ $job->location->name }}</td>
                                                <td class="text-center">{{ $job->type }}</td>
                                                <td>
                                                    <div class="status-box">
                                                        <span style="color: {{ $job->status ? 'green' : 'red' }};">
                                                            {{ $job->status ? 'Open' : 'Closed' }}
                                                        </span>
                                                        <span class="status-indicator {{ $job->status ? 'open' : 'closed' }}"></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-between">
                                                        <!-- Open button -->
                                                        <div class="flex-fill me-1">
                                                            <form action="{{ route('jobs.toggles', $job->id) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" name="status" value="open" class="btn btn-sm btn-primary w-100">Open/Close</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="flex-fill me-1">
                                                            <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                        </div>
                                                        <div class="flex-fill ms-1">
                                                            <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                            </form>
                                                        </div>
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
