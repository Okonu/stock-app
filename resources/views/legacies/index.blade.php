@extends('layouts.master')


@section('top')

    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    {{--<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">--}}
    @include('sweet::alert')
@endsection

@section('content')
<div class="box box-success">

<div class="box-header">
    <h3 class="box-title">System Stock</h3>
</div>

<!-- /.box-header -->
<div class="box-body">
    <table id="legacy-table" class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Garden</th>
            <th>Invoice</th>
            <th>Package Number</th>
            <th>Grade</th>
            <th>Package Type</th>
            <!--<th>Actions</th>-->
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<!-- /.box-body -->
</div>

<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">

                <h3 class="box-title">Import Current Stock</h3>
                <br><br>
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fa fa-check"></i>Success!&nbsp;
                        {{session('success')}}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fa fa-ban"></i>Error!&nbsp;
                        {{session('error')}}
                    </div>
                @endif
            </div>

            <form role="form" action="{{ route('api.imports') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="file">Select File:</label>
        <input type="file" name="file" id="file" required>
    </div>
    <button type="submit" class="btn btn-primary">Upload</button>
</form>

        </div>
    </div>
</div>

@endsection

@section('bot')

    <!-- DataTables -->
    <script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>

    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>--}}

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
        var table = $('#legacy-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.legacies') }}",
             lengthMenu: [50, 100, 200, 500],
            pageLength: 50, 
            columns: [
                {
                    data: 'null', name: 'null', orderable: false, searchable: false,
                    render: function (data, type, row, meta){
                        var rowNumber = meta.row + 1;
                        return rowNumber;
                    }
                },
                {data: 'garden', name: 'garden'},
                {data: 'invoice', name: 'invoice'},
                {data: 'qty', name: 'qty'},
                {data: 'grade', name: 'grade'},
                {data: 'package', name: 'package'},
                // {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Import Current Stock');
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
                    url : "{{ url('legacies') }}" + '/' + id,
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
                    if (save_method == 'add') url = "{{ route('api.imports') }}";
                    else url = "{{ url('imports') . '/' }}" + id;

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

