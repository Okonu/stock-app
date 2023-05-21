@extends('layouts.master')

@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">List of Warehouses</h3>
    </div>

    <div class="box-header">
        <a href="#" onclick="addForm()" class="btn btn-success"><i class="fa fa-plus"></i> Add a New Warehouse</a>
    </div>

    <!-- /.box-header -->
    <div class="box-body">
        <table id="warehouses-table" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Bays</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>

@include('warehouses.create')

@endsection

@section('bot')
    <!-- DataTables -->
    <script src="{{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    <script type="text/javascript">
        var table = $('#warehouses-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.warehouses') }}",
            columns: [
                {
                    data: 'null', name: 'null', orderable: false, searchable: false,
                    render: function (data, type, row, meta) {
                        var rowNumber = meta.row + 1;
                        return rowNumber;
                    }
                },
                { data: 'name', name: 'name' },
                { data: 'bays', name: 'bays', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // function formatBays(bays) {
        //     console.log(bays); // Log the bays array to the console
        //     var html = '<ul>';
        //     $data = (bays, function (index, bay) {
        //         console.log(bay); // Log each individual bay to the console
        //         console.log(typeof bay); // Log the type of bay
        //         if (bay && bay.name) {
        //             html += '<li>' + bay.name + '</li>';
        //         }
        //     });
        //     html += '</ul>';
        //     return html;
        // }

        function formatBays(bays) {
            var bayArray = bays.split(","); // Split the bays string into an array
            var html = '<ul>';
            bayArray.forEach(function (bay) {
                if (bay.trim() !== "") { // Ignore empty bays
                    html += '<li>' + bay.trim() + '</li>'; // Create <li> element for each bay
                }
            });
            html += '</ul>';
            return html;
        }


        function addForm() {
            save_method = "add";
            warehouseID = null;
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Add Warehouse');
        }

        function editForm(id) {
            save_method = 'edit';
            warehouseID = id;
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('warehouses') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit Warehouse');
                    $('#name').val(data.name);
                    $('#bays').val(data.bays);
                },
                error: function () {
                    alert("No Data Found");
                }
            });
        }

        function deleteData(id) {
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function () {
                $.ajax({
                    url: "{{ url('warehouses') }}" + '/' + id,
                    type: "POST",
                    data: { '_method': 'DELETE', '_token': csrf_token },
                    success: function (data) {
                        table.ajax.reload();
                        swal({
                            title: 'Success!',
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        });
                    },
                    error: function (data) {
                        swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '1500'
                        });
                    }
                });
            });
        }

        $(function () {
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()) {
                    var url;
                    if (save_method === 'add') {
                        url = "{{ route('warehouses.store') }}";
                    } else {
                        url = "{{ url('warehouses') }}" + '/' + warehouseID;
                    }

                    $.ajax({
                        url: url,
                        type: "POST",
                        data: new FormData($("#modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            });
                        },
                        error: function (data) {
                            swal({
                                title: 'Oops...',
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            });
                        }
                    });
                    return false;
                }
            });

            table.on('draw.dt', function () {
                table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                    var data = this.data();
                    var bays = data.bays;
                    var baysHtml = formatBays(bays);
                    $('td', this.node()).eq(2).html(baysHtml);
                });
            });
        });
    </script>
@endsection
