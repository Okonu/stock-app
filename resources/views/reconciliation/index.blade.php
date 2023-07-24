@extends('layouts.master')

@section('content')
<div class="card card-success">
    <div class="card-header">
        <h3 class="card-title">Stock Reconciliation</h3>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3 id="totalSysQty">{{ $data['stats']['totalSysQty'] }}<sup style="font-size: 20px"></sup></h3>
                        <p>Total System Quantity</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3 id="totalPhysQty">{{ $data['stats']['totalPhysQty'] }}<sup style="font-size: 20px"></sup></h3>
                        <p>Total Physical Quantity</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3 id="totalMismatchInvoices">{{ $data['stats']['totalMismatchInvoices'] }}<sup style="font-size: 20px"></sup></h3>
                        <p>Total Mismatch Invoices</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3 id="missingBagsQty">{{ $data['stats']['missingBagsQty'] }}<sup style="font-size: 20px"></sup></h3>
                        <p>Missing Bags Quantity</p>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <h4>Reconciliation Results</h4>
        
        <div class="box-header">
            <a href="{{ route('reconcileStockExport') }}" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Export Excel</a>
        </div>
        <table id="reconciliation" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>System Invoice</th>
                    <th>Physical Invoice</th>
                    <th>System Quantity</th>
                    <th>Physical Quantity</th>
                    <th>Garden</th>
                    <th>Grade</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('bot')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<script src="{{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

<script type="text/javascript">
  $(function () {
    var jsonData = {!! $jsonData !!};
    var reconciliationUrl = "{{ route('reconciliation.index') }}";

    $('#reconciliation').DataTable({
        processing: true,
        data: jsonData.data,
        columns: [
            {
                data: 'null', name: 'null', orderable: false, searchable: false,
                render: function (data, type, row, meta){
                    var rowNumber = meta.row + 1;
                    return rowNumber;
                }
            },
            { data: 'sys', name: 'System Invoice' },
            { data: 'phys', name: 'Physical Invoice' },
            { data: 'sys_Qty', name: 'System Quantity' },
            { data: 'phys_Qty', name: 'Physical Quantity' },
            { data: 'Garden', name: 'Garden' },
            { data: 'Grade', name: 'Grade' },
            { 
                data: 'Status',
                name: 'Status',
                createdCell: function (td, cellData, rowData, row, col) {
                    var buttonClass = 'btn btn-sm ';
                    if (cellData === 'match') {
                        buttonClass += 'btn-success';
                    } else if (cellData === 'mismatch') {
                        buttonClass += 'btn-danger';
                    } else if (cellData === 'unmatched') {
                        buttonClass += 'btn-warning';
                    }

                    $(td).html('<button type="button" class="' + buttonClass + '">' + cellData + '</button>');
                }
            }
        ]
    });
});

</script>
@endsection
