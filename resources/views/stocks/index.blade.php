@extends('layouts.master')

@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
<div class="box box-success">
<div class="box-header">
    <h3 class="box-title"><strong>Physical Stock</strong></h3>
</div>

    <div class="box-header" style="display:none">
    <a href="" id="generate-report-btn" class="btn btn-primary"><i class="fa fa-file"></i> Monthly Stock Report</a>
      @include('stocks.reports')
    <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
    </div>

        <div class="box-header">
            <a href="{{ route('exportExcel.stockAll') }}" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Export Excel</a>
        </div>

        <div class="box-body table-responsive">
            <table id="stocks-table" class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th> 
                        <th>User</th>
                        <th>Warehouse</th>
                        <th>Bays</th>
                        <th>Producer</th>
                        <th>Garden</th>
                        <th>Grade</th>
                        <th>Pkg Type</th>
                        <th>Invoice</th>
                        <th>Pkg No.</th>
                        <th>Year</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Stock Modal -->
    <div class="modal fade" id="stock-modal" tabindex="-1" role="dialog" aria-labelledby="stock-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="stock-modal-label">Stock Details</h4>
                </div>
                <div class="modal-body">
                    <!-- Stock details -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Monthly Report</h4>
            </div>
            <div class="modal-body">
                <!-- Table content -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
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
    
        function initializeTooltips() {
          $('[data-toggle="tooltip"]').tooltip();
        }
        
        
        $(document).ready(function () {
            
            var table = $('#stocks-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('api.stocks') }}"
                },
                lengthMenu: [50, 100, 200, 500], 
        pageLength: 50, 
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
                        data: 'mismatch',
                        name: 'mismatch',
                        render: function (data) {
                            if (data) {
                                return '<span class="label label-danger" data-toggle="tooltip"  data-placement="top" title="some comment here...">Mismatch</span>';
                            } else {
                                return '<span class="label label-success" data-toggle="tooltip"  data-placement="top" title="All good!">Match</span>';
                            }
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
                    //     data: 'comment',
                    //     name: 'comment',
                    //     render: function (data) {
                    //         if (data) {
                    //             return '<span class="label label-danger">Comment</span>';
                    //         } else {
                    //             return '<span class="label label-success">Comment Here</span>';
                    //         }
                    //     }
                    // },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            var viewButton = '<button class="btn btn-info btn-xs view-stock" data-id="' + row.id + '">View</button>';
                            return viewButton;
                        }
                    }
                ]
            });
           
            $('#generate-report-btn').on('click', function () {
                console.log('Button clicked'); 
                var month = $('#month').val();
                var warehouseId = "{{ $warehouse_id }}"; 
                var url = "{{ route('stocks.reports', ['warehouse_id' => ':warehouse_id', 'month' => ':month']) }}";
                url = url.replace(':warehouse_id', warehouseId).replace(':month', month);

                var csrfToken = $('#csrf-token').val();

                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        console.log(response);
                        
                        var tableData = response.data; 
  
                        var tableHtml = '<table id="report-table" class="table table-bordered">';
                        tableHtml += '<thead><tr><th>Column 1</th><th>Column 2</th></tr></thead>';
                        tableHtml += '<tbody>';

                        for (var i = 0; i < tableData.length; i++) {
                            tableHtml += '<tr>';
                            tableHtml += '<td>' + tableData[i].column1 + '</td>';
                            tableHtml += '<td>' + tableData[i].column2 + '</td>';
                            tableHtml += '</tr>';
                        }

                        tableHtml += '</tbody></table>';

                        var modalBody = $('#myModal .modal-body');
                        modalBody.html(tableHtml);
                        $('#myModal').modal('show');

                        $('#report-table').DataTable();
                    },
                    error: function (xhr, status, error) {
                        
                        console.log(error);
                    }
                });
            });
            // View stock details
            $('#stocks-table tbody').on('click', '.view-stock', function () {
                var stockId = $(this).data('id');
                $.ajax({
                    url: "{{ route('api.stocks') }}",
                    data: { id: stock_id },
                    success: function (response) {
                        if (response.success) {
                            var stock = response.stock;
                            var modalBody = '<table class="table table-bordered">' +
                                '<tr><th>Field</th><th>Value</th></tr>' +
                                '<tr><td>User</td><td>' + stock.user_name + '</td></tr>' +
                                '<tr><td>Warehouse</td><td>' + stock.warehouse_name + '</td></tr>' +
                                '<tr><td>Bays</td><td>' + stock.bays + '</td></tr>' +
                                '<tr><td>Producer</td><td>' + stock.owner_name + '</td></tr>' +
                                '<tr><td>Garden</td><td>' + stock.garden_name + '</td></tr>' +
                                '<tr><td>Grade</td><td>' + stock.garden_name + '</td></tr>' +
                                '<tr><td>Package Type</td><td>' + stock.package_name + '</td></tr>' +
                                '<tr><td>Invoice</td><td>' + stock.invoice + '</td></tr>' +
                                '<tr><td>Package No.</td><td>' + stock.qty + '</td></tr>' +
                                '<tr><td>Production Year</td><td>' + stock.year + '</td></tr>' +
                                '<tr><td>Remarks</td><td>' + stock.remark + '</td></tr>' +
                                '<tr><td>Status</td><td>' + (stock.mismatch ? '<span class="label label-danger">Mismatch</span>' : '<span class="label label-success">Match</span>') + '</td></tr>' +
                                '</table>';
                            $('#stock-modal .modal-body').html(modalBody);
                            $('#stock-modal').modal('show');
                        } else {
                            alert('Failed to fetch stock details. Please try again.');
                        }
                    },
                    error: function () {
                        alert('An error occurred while fetching stock details. Please try again.');
                    }
                });
            });
      
        });
    </script>
@endsection
