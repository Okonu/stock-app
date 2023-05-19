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
    @include('stocks.tableu')

@endsection

@section('bot')

     <!-- DataTables -->
     <script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>

    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    {{--<script>--}}
    {{--$(function () {--}}
    {{--$('#items-table').DataTable()--}}
    {{--$('#example2').DataTable({--}}
    {{--'paging'      : true,--}}
    {{--'lengthChange': false,--}}
    {{--'searching'   : false,--}}
    {{--'ordering'    : true,--}}
    {{--'info'        : true,--}}
    {{--'autoWidth'   : false--}}
    {{--})--}}
    {{--})--}}
    {{--</script>--}}


    <script type="text/javascript">
        var table = $('#stocks-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.stocks') }}",
            columns: [
                {
                    data: 'null', name: 'null', orderable: false, searchable: false,
                    render: function (data, type, row, meta){
                        var rowNumber = meta.row + 1;
                        return rowNumber;
                    }
                },
                {data: 'warehouse_name', name: 'warehouse_name'},
                {data: 'bay_name', name: 'bay_name'},
                {data: 'owner_name', name: 'owner_name'},
                {data: 'garden_name', name: 'garden_name'},
                {data: 'grade_name', name: 'grade_name'},
                {data: 'package_name', name: 'package_name'},
                {data: 'invoice', name: 'invoice'},
                {data: 'qty', name: 'qty'},
                {data: 'year', name: 'year'},
                // {data: 'remark', name: 'remark'}
                // {data: 'action', name: 'action', orderable: false, searchable: false}
            ],

            // Click event to each Row
            createdRow: function (row, data, dataIndex) {
                $(row).on('click', function () {
                    // populating modal with stock details
                    var modal = $('#stock-modal');
                    modal.find('.modal-body').html('Stock details: ' + data.stock_taken);
                    modal.modal('show');
                });
            }
        });

        // export PDF button click in the mb_encoding_aliases
        $('#export-pdf-btn').on('click', function () {
            //export pdf logic

            var selectedStock = table.row('.selected').data();
            if (selectedStock) {
                var stockId = selectedStock.id;
                // Replace 'id' with the appropriate property name for the stock ID
        // Perform the export PDF operation using the stock ID
        // Example: window.location.href = '/export-pdf/' + stockId;
            }
        });

    </script>

@endsection
