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

    <div class="box-header">
        <button id="exportButton" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Export Excel for Selected Month</button><br><br>
        <div style="display:none">
        <label for="selectYear">Select Year:</label>
        <select id="selectYear" class="form-control">
            @php
                $currentYear = date('Y');
                for ($year = $currentYear; $year >= $currentYear - 10; $year--) {
                    echo "<option value='$year'>$year</option>";
                }
            @endphp
        </select>
        </div>
        <label for="selectMonth">Select Month:</label>
        <select id="selectMonth" class="form-control">
            <option value="">All Months</option>
            @for ($month = 1; $month <= 12; $month++)
                <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}">
                    {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                </option>
            @endfor
        </select>
    </div>

    <div class="box-body table-responsive">
        <table id="stocks-table" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Date taken</th>
                    <th>Warehouse</th>
                    <th>Bays</th>
                    <th>Producer</th>
                    <th>Garden</th>
                    <th>Grade</th>
                    <th>Pkg Type</th>
                    <th>Invoice</th>
                    <th>Pkg No.</th>
                    <th>Year</th>
                    <th>Remarks</th>
                    <!--<th>Actions</th>-->
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

        function initializeTooltips() {
            $('[data-toggle="tooltip"]').tooltip();
        }

        $(document).ready(function () {
            var table;

            function initializeTable(month, year) {
                table = $('#stocks-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('api.stocks') }}",
                        data: function (d) {
                            if (month) {
                                d.month = month;
                            }
                            if (year) {
                                d.year = year;
                            }
                        }
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
                        data: 'created_at',
                        name: 'created_at',
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
                        }
                    ]
                });
            }
            
            initializeTable(null, null);

            // Handle the click event of the export button
            $('#exportButton').click(function () {
                var selectedMonth = $('#selectMonth').val();
                var selectedYear = $('#selectYear').val();
                if (selectedMonth) {
                    // Redirect to the export route with the selected month and year as query parameters
                    window.location.href = "{{ route('exportExcel.stockAll') }}?month=" + selectedMonth + "&year=" + selectedYear;
                }
            });
            
            // Handle the change event of the month and year selection dropdowns
            $('#selectMonth, #selectYear').change(function () {
                var selectedMonth = $('#selectMonth').val();
                var selectedYear = $('#selectYear').val();
                if (selectedMonth || selectedYear) {
                    // Reload the DataTable with the selected month and year filters
                    table.destroy();
                    initializeTable(selectedMonth, selectedYear);
                } else {
                    // Clear the table if neither month nor year is selected
                    table.clear().draw();
                }
            });
        });
    </script>
@endsection
