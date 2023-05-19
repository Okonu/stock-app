<div class="box box-success">

        <div class="box-header">
            <h3 class="box-title"><strong>Stock Taken</strong></h3>


        </div>

        <div class="box-header">

            <a href="{{ route('exportPDF.stockAll') }}" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
            <a href="{{ route('exportExcel.stockAll') }}" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Export Excel</a>
        </div>




        <!-- /.box-header -->
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
                    <!-- <th>Remarks</th> -->
                    <!-- <th>Actions</th> -->
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>