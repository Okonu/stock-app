@extends('admin.layouts.app')

@section('content')
<div class="content-body">
            <!-- row -->
            <div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Skills</h4>
                    </div>
                    <div class="col text-end">
                         <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#basicModal">Add</button>
                    </div>
                </div>
            </div>
               <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th><strong>NAME</strong></th>
                                <th><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($skills as $skill)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="w-space-no">{{ $skill->skill }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        @if (!$skill->in_use)
                                            <form action="{{ route('deleteSkills', $skill->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE') <!-- Change to DELETE method -->
                                                <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                           
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="basicModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Skill</h5>
                            <button type="button" class="close" data-bs-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <form action="{{ route('addSkills') }}" method="POST">
                        @csrf <!-- Include the CSRF token -->

                        <div class="form-group">
                            <label for="location_name">Skill Name</label>
                            <input type="text" class="form-control" name="skill" id="skill" placeholder="Enter skill name">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button> <!-- Removed the duplicate 'type' attribute -->
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
@endsection