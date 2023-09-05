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
<<<<<<< HEAD
    <h3 class="box-title"><strong>Physical Stock</strong></h3>
</div>

    <div class="box-header" style="display:none">
    <a href="" id="generate-report-btn" class="btn btn-primary"><i class="fa fa-file"></i> Monthly Stock Report</a>
      @include('stocks.reports')
=======
    <h3 class="box-title"><strong>Stock Taken</strong></h3>
</div>

<div class="box-header">
    <!-- <div class="form-group">
        <label for="month">Select Month:</label>
        <select id="month" name="month" class="form-control">
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="2">March</option>
            <option value="2">April</option>
            <option value="2">May</option>
            <option value="2">June</option>
            <option value="2">July</option>
            <option value="2">August</option>
            <option value="2">September</option>
            <option value="2">October</option>
            <option value="2">November</option>
            <option value="2">December</option>
        </select>
    </div> -->
    <a href="{{route('stocks.reports')}}" id="generate-report-btn" class="btn btn-primary"><i class="fa fa-file"></i> Monthly Stock Report</a>
>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
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
<<<<<<< HEAD
=======
    </div>

    <!-- Import Form -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Import Stock Data</h3>
        </div>
        <div class="panel-body">
            <form id="import-form" method="POST" action="{{ route('api.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="import-file">Select Excel File:</label>
                    <input type="file" id="import-file" name="file">
                </div>
                <button type="submit" class="btn btn-primary">Import</button>
            </form>
        </div>
>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
    </div>
    <div class="box box-danger">
        <div class="box-header">
            <h3 class="box-title"><strong>Mismatched Data</strong></h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Garden</th>
                        <th>Invoice</th>
                        <th>Qty</th>
                        <th>Grade</th>
                        <th>Package</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mismatches as $mismatch)
                        <tr>
                            <td>{{ $mismatch['garden'] }}</td>
                            <td>{{ $mismatch['invoice'] }}</td>
                            <td>{{ $mismatch['qty'] }}</td>
                            <td>{{ $mismatch['grade'] }}</td>
                            <td>{{ $mismatch['package'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p>Total Mismatched Quantity: {{ $totalMismatchQty }}</p>
        </div>
    </div>
    @include('stocks.reports')
@include('stocks.owners')
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

<<<<<<< HEAD
=======
    <!-- Mismatch Modal -->
    <div class="modal fade" id="mismatch-modal" tabindex="-1" role="dialog" aria-labelledby="mismatch-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="mismatch-modal-label">Mismatched Data</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Garden</th>
                                <th>Invoice</th>
                                <th>Qty</th>
                                <th>Grade</th>
                                <th>Package</th>
                            </tr>
                        </thead>
                        <tbody id="mismatch-table-body">
                            <!-- Mismatched data rows will be added dynamically -->
                        </tbody>
                    </table>
                    <p id="no-mismatch-msg" style="display: none;">No mismatches found.</p>
                    <p id="total-mismatch-qty"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
    <div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Monthly Report</h4>
            </div>
            <div class="modal-body">
<<<<<<< HEAD
                <!-- Table content -->
=======
                <!-- Table content will be dynamically added here -->
>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
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
<<<<<<< HEAD
=======
                        data: 'remark',
                        name: 'remark',
                        render: function (data) {
                            return data ? data : '-';
                        }
                    },
                    { 
>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
                        data: 'mismatch',
                        name: 'mismatch',
                        render: function (data) {
                            if (data) {
<<<<<<< HEAD
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
=======
                                return '<span class="label label-danger">Mismatch</span>';
                            } else {
                                return '<span class="label label-success">Match</span>';
                            }
                        }
                    },
>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
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

<<<<<<< HEAD
=======
            // $('#generate-report-btn').on('click', function () {
            //     var month = $('#month').val();
            //     var warehouseId = "{{ $warehouse_id }}"; // Get the warehouse ID from your PHP variable
            //     var url = "{{ route('stocks.reports', ['warehouse_id' => ':warehouse_id', 'month' => ':month']) }}";
            //     url = url.replace(':warehouse_id', warehouseId).replace(':month', month);
            //     window.location.href = url;
            // });
            $('#generate-report-btn').on('click', function () {
                console.log('Button clicked'); 
                var month = $('#month').val();
                var warehouseId = "{{ $warehouse_id }}"; // Get the warehouse ID from your PHP variable
                var url = "{{ route('stocks.reports', ['warehouse_id' => ':warehouse_id', 'month' => ':month']) }}";
                url = url.replace(':warehouse_id', warehouseId).replace(':month', month);

>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
                var csrfToken = $('#csrf-token').val();

                // Send an AJAX request
                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        console.log(response);
<<<<<<< HEAD
                        
                        var tableData = response.data; 
  
=======
                        // Handle the response
                        var tableData = response.data; // Assuming the response contains the data for the table

                        // Construct the HTML table
>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
                        var tableHtml = '<table id="report-table" class="table table-bordered">';
                        tableHtml += '<thead><tr><th>Column 1</th><th>Column 2</th></tr></thead>';
                        tableHtml += '<tbody>';

                        for (var i = 0; i < tableData.length; i++) {
                            tableHtml += '<tr>';
<<<<<<< HEAD
                            tableHtml += '<td>' + tableData[i].column1 + '</td>';
=======
                            tableHtml += '<td>' + tableData[i].column1 + '</td>'; // Adjust the column names accordingly
>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
                            tableHtml += '<td>' + tableData[i].column2 + '</td>';
                            tableHtml += '</tr>';
                        }

                        tableHtml += '</tbody></table>';

<<<<<<< HEAD
=======
                        // Display the table in a modal or on the page
                        // Example: using Bootstrap modal
>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
                        var modalBody = $('#myModal .modal-body');
                        modalBody.html(tableHtml);
                        $('#myModal').modal('show');

<<<<<<< HEAD
                        $('#report-table').DataTable();
                    },
                    error: function (xhr, status, error) {
                        
=======
                        // Initialize the DataTables plugin for the generated table
                        $('#report-table').DataTable();
                    },
                    error: function (xhr, status, error) {
                        // Handle the error
>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
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
<<<<<<< HEAD
      
=======

            // Display mismatched data in modal
            $('#mismatch-modal').on('show.bs.modal', function () {
                var mismatches = @json($mismatches);
                if (mismatches.length > 0) {
                    var mismatchTableBody = '';
                    mismatches.forEach(function (mismatch) {
                        mismatchTableBody += '<tr>' +
                            '<td>' + mismatch.garden + '</td>' +
                            '<td>' + mismatch.invoice + '</td>' +
                            '<td>' + mismatch.qty + '</td>' +
                            '<td>' + mismatch.grade + '</td>' +
                            '<td>' + mismatch.package + '</td>' +
                            '</tr>';
                    });
                    $('#mismatch-table-body').html(mismatchTableBody);
                    $('#no-mismatch-msg').hide();
                    $('#total-mismatch-qty').text('Total Mismatched Quantity: ' + @json($totalMismatchQty));
                } else {
                    $('#mismatch-table-body').empty();
                    $('#no-mismatch-msg').show();
                    $('#total-mismatch-qty').empty();
                }
            });       

            // Submit import form
            $('#import-form').validator().on('submit', function (e) {
                if (e.isDefaultPrevented()) {
                    // Form validation failed
                    alert('Please fill in all the required fields.');
                } else {
                    // Form validation succeeded
                    e.preventDefault();
                    var formData = new FormData($(this)[0]);
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.success) {
                                alert('Stock data imported successfully.');
                                location.reload();
                            } else {
                                alert('Failed to import stock data. Please try again.');
                            }
                        },
                        error: function () {
                            alert('An error occurred while importing stock data. Please try again.');
                        }
                    });
                }
            });
>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
        });
    </script>
@endsection
