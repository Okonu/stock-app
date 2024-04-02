@extends('layouts.master')

@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header">
            <h3 class="box-title">List of System Users</h3>
        </div>
        <div class="box-header">
            <a href="/register" class="btn btn-success"><i class="fa fa-plus"></i> Add User</a>
            <button onclick="deleteData()" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
        </div>
        <div class="box-body">
            <table id="user-table" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('bot')

    <!-- DataTables -->
    <script src="{{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    <script type="text/javascript">
        var table = $('#user-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.users') }}",
            columns: [
                {
                    data: 'id', name: 'id', orderable: false, searchable: false,
                    render: function (data) {
                        return '<input type="checkbox" class="user-checkbox" value="' + data + '">';
                    }
                },
                {
                    data: 'null', name: 'null', orderable: false, searchable: false,
                    render: function (data, type, row, meta) {
                        var rowNumber = meta.row + 1;
                        return rowNumber;
                    }
                },
                {data: 'name', name: 'name'},
                {data: 'phone', name: 'phone'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('user') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit User');

                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#phone').val(data.phone);
                },
                error : function() {
                    alert("Nothing Data");
                }
            });
        }

        function deleteData(id) {
            swal({
                title: 'Are you sure?',
                text: 'Once deleted, you will not be able to recover this user!',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then((confirmDelete) => {
                if (confirmDelete) {
                    $.ajax({
                        url: '/user/' + id,
                        type: 'DELETE',
                        success: function (data) {
                            table.ajax.reload();
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: 1500,
                            });
                        },
                        error: function (data) {
                            swal({
                                title: 'Oops...',
                                text: data.message,
                                type: 'error',
                                timer: 1500,
                            });
                        },
                    });
                }
            });
        }

        $(document).on('change', '.user-checkbox', function () {
            var user_id = $(this).val();
            toggleUserSelection(user_id);
        });

    </script>

@endsection
