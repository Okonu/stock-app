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
            <h3 class="box-title"><strong>Stock Taken</strong></h3>
        </div>

        <div class="box-header">
            <a href="{{ route('exportExcel.stockAll') }}" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Export Excel</a>
        </div>

        <div class="box-body">
            <table id="stocks-table" class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th> 
                        <th>Warehouse</th>
                        <th>Bays</th>
                        <th>Farm Owner</th>
                        <th>Garden</th>
                        <th>Grade</th>
                        <th>Package Type</th>
                        <th>Invoice</th>
                        <th>Package No.</th>
                        <th>Production Year</th>
                        <th>Remarks</th>
                        <th>Status</th> <!-- 'mismatch' column -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
    @include('stocks.bags')

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
                    <!-- Stock details will be populated here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
        $(document).ready(function () {
            var table = $('#stocks-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('api.stocks') }}",
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
                    { data: 'warehouse_name', name: 'warehouse_name' },
                    { data: 'warehouse_bay_name', name: 'warehouse_bay_name' },
                    { data: 'owner_name', name: 'owner_name' },
                    { data: 'garden_name', name: 'garden_name' },
                    { data: 'grade_name', name: 'grade_name' },
                    { data: 'package_name', name: 'package_name' },
                    { data: 'invoice', name: 'invoice' },
                    { data: 'qty', name: 'qty' },
                    { data: 'year', name: 'year' },
                    { data: 'remark', name: 'remark' },
                    {
                        data: 'mismatch',
                        name: 'mismatch',
                        render: function (data, type, row) { 
                            if (data) {
                                return '<span class="text-danger">Mismatched</span>';
                            } else {
                                return '<span class="text-success">Matched</span>';
                            }
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                createdRow: function (row, data, dataIndex) {
                    $(row).on('click', function () {
                        var modal = $('#stock-modal');
                        modal.find('.modal-body').html('Stock details: ' + data.stock_taken);
                        modal.modal('show');
                    });
                }
            });

            // Export PDF button click
            $('#export-pdf-btn').on('click', function () {
                var selectedStock = table.row('.selected').data();
                if (selectedStock) {
                    var stockId = selectedStock.id;
                    // Replace 'id' with the appropriate property name for the stock ID
                    // Perform the export PDF operation using the stock ID
                    // Example: window.location.href = '/export-pdf/' + stockId;
                }
            });

            // Import Form Submission
            $('#import-form').on('submit', function (e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        // Handle successful import
                        alert('Import successful!');
                        // Reload the DataTable
                        table.ajax.reload();
                    },
                    error: function (xhr, status, error) {
                        // Handle import error
                        alert('Import failed: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
