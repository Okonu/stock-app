<div class="box box-success">

        <div class="box-header">
            <h3 class="box-title"><strong>Physical Stock Taken</strong></h3>


        </div>

        <div class="box-header">
            <a href="{{ route('exportExcel.stockAll') }}" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Export Excel</a>
        </div>




        <!-- /.box-header -->
        <div class="box-body">
            <table id="stocks-table" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Taken By</th>
                    <th>Warehouse</th>
                    <th>Bays</th>
                    <th>Producer</th>
                    <th>Garden</th>
                    <th>Grade</th>
                    <th>Package Type</th>
                    <th>Invoice</th>
                    <th>Package No.</th>
                    <th>Production Year</th>
                    <th>Remarks</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>

    @section('bot')
    <!-- DataTables -->
    <script src="{{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            var table = $('#stocks-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('api.stocks') }}"
                },
<<<<<<< HEAD
                lengthMenu: [50, 100, 200, 500], 
                pageLength: 50, 
=======
>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
                columns: [
                    {
                        data: null,
                        name: 'null',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            var rowNumber = meta.row + 1;
                            return rowNumber;
                        }
                    },
                    { 
                        data: 'user_name',
                        name: 'user.name',
                        render: function (data) {
                            return data ? data : '-';
                        }
                    },
                    { 
                        data: 'warehouse_name',
                        name: 'warehouse.name',
                        render: function (data) {
                            return data ? data : '-';
                        }
                    },
                    { 
                       data: 'warehouse_bay_name', 
                       name: 'warehouse_bay_name',
                        render: function (data) {
                            return data ? data : 'N/A';
                        }
                    },
                    { 
                        data: 'owner_name',
                        name: 'owner.name',
                        render: function (data) {
                            return data ? data : '-';
                        }
                    },
                    { 
                        data: 'garden_name',
                        name: 'garden.name',
                        render: function (data) {
                            return data ? data : '-';
                        }
                    },
                    { 
                        data: 'grade_name',
                        name: 'grade.name',
                        render: function (data) {
                            return data ? data : '-';
                        }
                    },
                    { 
                        data: 'package_name',
                        name: 'package.name',
                        render: function (data) {
                            return data ? data : '-';
                        }
                    },
                    { 
                        data: 'invoice',
                        name: 'invoice',
                        render: function (data) {
                            return data ? data : '-';
                        }
                    },
                    { 
                        data: 'qty',
                        name: 'qty',
                        render: function (data) {
                            return data ? data : '-';
                        }
                    },
                    { 
                        data: 'year',
                        name: 'year',
                        render: function (data) {
                            return data ? data : '-';
                        }
                    },
                    { 
                        data: 'remark',
                        name: 'remark',
                        render: function (data) {
                            return data ? data : '-';
                        }
                    },
                    // { 
                    //     data: 'mismatch',
                    //     name: 'mismatch',
                    //     render: function (data) {
                    //         if (data) {
                    //             return '<span class="label label-danger">Mismatch</span>';
                    //         } else {
                    //             return '<span class="label label-success">Match</span>';
                    //         }
                    //     }
                    // },
                    // {
                    //     data: 'actions',
                    //     name: 'actions',
                    //     orderable: false,
                    //     searchable: false,
                    //     render: function (data, type, row, meta) {
                    //         var viewButton = '<button class="btn btn-info btn-xs view-stock" data-id="' + row.id + '">View</button>';
                    //         return viewButton;
                    //     }
                    // }
                ]
            });
        });
    </script>
@endsection