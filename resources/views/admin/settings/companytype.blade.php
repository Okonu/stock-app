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
                        <h4 class="card-title">Partner Type</h4>
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
                            @foreach ($companyTypes as $type)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="w-space-no">{{ $type->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        @if (!$type->in_use)
                                            <form action="{{ route('deleteType', $type->id) }}" method="POST" style="display: inline;">
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
                            <h5 class="modal-title">Add Partner Type</h5>
                            <button type="button" class="close" data-bs-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                       <div class="modal-body">
                        <form action="{{ route('addCompanyType') }}" method="POST">
                        @csrf <!-- Include the CSRF token -->

                        <div class="form-group">
                            <label for="location_name">Partner Type </label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Partrner Type">
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