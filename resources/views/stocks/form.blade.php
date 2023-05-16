<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form  id="form-item" method="post" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data" >
                {{ csrf_field() }} {{ method_field('POST') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title"></h3>
                </div>


                <div class="modal-body">
                    <input type="hidden" id="id" name="id">


                    <div class="box-body">
                        <div class="form-group">
                            <label >warehouses</label>
                            {!! Form::select('warehouse_id', $warehouse, null, ['class' => 'form-control select', 'placeholder' => '-- Choose Warehouse --', 'id' => 'warehouse_id', 'required']) !!}
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label >Bays</label>
                            {!! Form::select('bay_id', $bay, null, ['class' => 'form-control select', 'placeholder' => '-- Choose Bay --', 'id' => 'bay_id', 'required']) !!}
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label >Farm Owner</label>
                            {!! Form::select('owner_id', $owner, null, ['class' => 'form-control select', 'placeholder' => '-- Choose Farm Owner --', 'id' => 'owner_id', 'required']) !!}
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label >Garden</label>
                            {!! Form::select('garden_id', $garden, null, ['class' => 'form-control select', 'placeholder' => '-- Choose Garden --', 'id' => 'garden_id', 'required']) !!}
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label >Tea Grades</label>
                            {!! Form::select('grade_id', $grade, null, ['class' => 'form-control select', 'placeholder' => '-- Choose Tea Grade --', 'id' => 'grade_id', 'required']) !!}
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label >Package Type</label>
                            {!! Form::select('package_id', $package, null, ['class' => 'form-control select', 'placeholder' => '-- Choose Package Type --', 'id' => 'package_id', 'required']) !!}
                            <span class="help-block with-errors"></span>
                        </div>
                        
                        <div class="form-group">
                            <label >Invoice No.</label>
                            <input type="text" class="form-control" id="invoice" name="invoice" required>
                            <span class="help-block with-errors"></span>
                        </div>
                        
                        <div class="form-group">
                            <label >Number of Bags</label>
                            <input type="text" class="form-control" id="qty" name="qty" required>
                            <span class="help-block with-errors"></span>
                        </div>
                        
                        <div class="form-group">
                            <label >Production Year</label>
                            <input type="text" class="form-control" id="year" name="year" required>
                            <span class="help-block with-errors"></span>
                        </div>
                        
                        <div class="form-group">
                            <label >Remarks</label>
                            <input type="text" class="form-control" id="remark" name="remark" required>
                            <span class="help-block with-errors"></span>
                        </div>

                    </div>
                    <!-- /.box-body -->

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
