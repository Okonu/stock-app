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
            <h3 class="box-title">Stock Taken</h3>


        </div>

        <div class="box-header">
            <a onclick="addForm()" class="btn btn-success" ><i class="fa fa-plus"></i> Take New Stock</a>
            <a href="{{ route('exportPDF.stockAll') }}" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
            <a href="{{ route('exportExcel.stockAll') }}" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Export Excel</a>
        </div>




        <!-- /.box-header -->
        <div class="box-body">
            <table id="stocks-table" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>ID</th>
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
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>


    @include('stocks.form')

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

    // <script>
    //     $(function () {

    //         //Colorpicker
    //         $('.my-colorpicker1').colorpicker()
    //         //color picker with addon
    //         $('.my-colorpicker2').colorpicker()

    //         //Timepicker
    //         $('.timepicker').timepicker({
    //             showInputs: false
    //         })
    //     })
    // </script>

    <script type="text/javascript">
        var table = $('#stocks-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.stocks') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'warehouse_name', name: 'warehouse_name'},
                {data: 'bay_name', name: 'bay_name'},
                {data: 'owner_name', name: 'owner_name'},
                {data: 'garden_name', name: 'garden_name'},
                {data: 'grade_name', name: 'grade_name'},
                {data: 'package_name', name: 'package_name'},
                {data: 'invoice', name: 'invoice'},
                {data: 'qty', name: 'qty'},
                {data: 'year', name: 'year'},
                {data: 'remark', name: 'remark'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Take New Stock');
        }

        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('stocks') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit Stock taken');

                    $('#id').val(data.id);
                    $('#warehouse_id').val(data.warehouse_id);
                    $('#bay_id').val(data.bay_id);
                    $('#owner_id').val(data.owner_id);
                    $('#garden_id').val(data.garden_id);
                    $('#grade_id').val(data.grade_id);
                    $('#package_id').val(data.package_id);
                    $('#invoice').val(data.invoice);
                    $('#qty').val(data.qty);
                    $('#year').val(data.year);
                    $('#remark').val(data.remark);
                },
                error : function() {
                    alert("No Data");
                }
            });
        }

        function deleteData(id){
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
                    url : "{{ url('stocks') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        table.ajax.reload();
                        swal({
                            title: 'Success!',
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        })
                    },
                    error : function () {
                        swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '1500'
                        })
                    }
                });
            });
        }

        $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('stocks') }}";
                    else url = "{{ url('stocks') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
//                      data : $('#modal-form form').serialize(),
                        data: new FormData($("#modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            swal({
                                title: 'Oops...',
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            })
                        }
                    });
                    return false;
                }
            });
        });
    </script>

@endsection
