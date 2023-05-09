@extends('layouts.master')

@section('top')
<link rel="stylesheet" href="{{asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" />
@endsection

@section('content')
<div class="box box-success">

    <div class="box-header">
        <h3 class="box-title">Stock Information</h3>
        <a onclick="addForm()" class="btn btn-success pull-right" style="margin-top: -8px;"><i class="fa fa-plus"></i>Take Stock</a>
    </div>

    <div class="box-header">
        <table id="products-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Warehouse</th>
                    <th>Bay</th>
                    <th>Owner</th>
                    <th>Garden</th>
                    <th>Grade</th>
                    <th>Package Type</th>
                    <th>Package Number</th>
                    <th>Production Year</th>
                    <th>Invoice Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@include('stock.form')
@endsection

@section('bot')
  <!-- dataTables   -->
  <script src=" {{asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>

  {{--Validator--}}
  <script src="{{asset('assets/validator/validator.min.js')}}"></script>

  {{--<script>--}}
  {{--$(function() {--}}
  {{--$('#items-table').DataTable()--}}
  {{--$('#example2').DataTable({--}}
  {{--'paging' : true,--}}
  {{--'lengthChange' : false,--}}
  {{--'searching' : false,--}}
  {{--'ordering' : true,--}}
  {{--'info' : true,--}}
  {{--'autoWidth' : true,--}}
  {{--})--}}
  {{--})--}}
  {{--</script>--}}

  <script type="text/javascript">
    var table = $('#stock-table').DataTable({
       processing: true,
       serverSide: true,
       ajax: "{{route('api.stock')}}",
       columns: [
        {data: 'id', name: 'id'},
        {data: 'warehouse_name', name: 'warehouse_name'},
        {data: 'bay_name', name: 'bay_name'},
        {data: 'owner_name', name: 'owner_name'},
        {data: 'garden_name', name: 'garden_name'},
        {data: 'grade', name: 'grade'},
        {data: 'packageType', name: 'package_type'},
        {data: 'qty', name: 'qty'},
        {data: 'year', name: 'year'},
        {data: 'invoice', name: 'invoice'},
        {data: 'action', name: 'action', orderable: false, searchable: false},
       ]
    });

    function addForm(){
        save_method = "add";
        $('input[name_method]').val('POST');
        $('#modal-form').modal('show');
        $('#method-form form')[0].reset();
        $('.modal-title').text('Take Stock');
    }

    function editForm(id){
        save_method = 'edit';
        $('input[name_method').val('PATCH');
        $('#modal-form form')[0].reset();
        $.ajax({
            url: "{{url('stock'}}" + '/' + id + '/edit',
            type: "GET", dataType: "JSON",
            success: function(data) {
                $('#modal-form').modal('show');
                $('.#modal-title').text('Edit Stock');

                $('#id').val(data.id);
                $('#warehouse_id').val(data.warehouse_id);
                $('#bay_id').val(data.bay_id);
                $('#owner_id').val(data.owner_id);
                $('#garden_id').val(data.garden_id);
                $('#grade_id').val(data.grade_id);
                $('#packageType_id').val(data.packageType_id);
                $('#qty').val(data.qty);
                $('#year').val(data.year);
                $('#invoice').val(data.invoice);
            },
            error: function() {
                alert("Nothing Selected")
            }
        });
    }

    function deleteData(id){
        var csrf_token = $('meta[name="csrf_token"]').attr('content');
        swal({
            title: 'Are you sure you want to delete?',
            text: "You won't be able to revert your changes!",
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: "#d33333",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Confirm"
        }).then(function(){
            $.ajax({
                url : "{{ url('stock') }}" + '/' + id,
                type: 'POST',
                data: {'_method' : 'DELETE', '_token' : csrf_token },
                success: function(data){
                    table.ajax.reload();
                    swal({
                        title : 'Success',
                        text: data.message,
                        type: 'success',
                        timer: '1500'
                    })
                },
                error: function(){
                    swal({
                        title : 'Error',
                        text: data.message,
                        type: 'error',
                        timer: '1500'
                    })
                }
            });
        });
    }

    $(function(){
        $('#modal-form form').validator.on('submit', function(e){
            if (!e.isDefaultPrevented()){
                var id = $('#id').val();
                if(save_method == 'add') url = "{{ url('stock') }}";
                else url = "{{ url('stock') . '/' }}" + id;

                $.ajax({
                    url: url,
                    type: "POST",
                    data: new FormData($("#modal-form form")[0]),
                    contentType: false,
                    processData: false,
                    success : function(data){
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
                            title: 'Error!',
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