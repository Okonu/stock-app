<div class="modal fade" id="modal-form" tabindex="1" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-item" method="post" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data">
                {{csrf_field()}}{{method_field('POST')}}    

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id"name="id">

                    <div class="box-body">
                        <div class="form-group">
                            <label>Warehouse Name</label>
                            {!! Form::select('warehouse_id', $warehouse, null, ['class' => 'form-control select', 'placeholder' => '--Choose Warehouse--', 'id' => 'warehouse_id', 'required']) !!}
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label>Bays</label>
                            {!! Form::select('bay_id', $bay, null, ['class' => 'form-control select', 'placeholder' => '--Choose Bay--', 'id'=>'bay_id', 'required']) !!}
                            <span class="help-block with-erros"></span>
                        </div>

                        <div class="form-group">
                            <label>Owner</label>
                            {!! Form::select('owner_id', $owner, null, ['class' => 'form-control select', 'placeholder' => '--Choose Owner--', 'id' => 'owner_id', 'required'])!!}
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label>Garden</label>
                            {!! Form::select('garden_id', $garden, null, ['class' =>'form-control select', 'placeholder' => '--Choose Garden--', 'id' => 'owner_id', 'required']) !!}
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label>Grade</label>
                            {!! Form::select('grade_id', $grade, null, ['class' =>'form-control select', 'placeholder' => '--Choose Grade--', 'id' => 'grade_id', 'required']) !!}
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label>Package Type</label>
                            {!! Form::select('packageType_id', $packageType, null, ['class' =>'form-control select', 'placeholder' => '--Choose Package Type--', 'id' => 'packageType_id', 'required']) !!}
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label>Package Number</label>
                            <input type="text" class="form-control" id="qty" name="qty" required>
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label>Production Year</label>
                            <input type="text" class="form-control" id="year" name="year" required>
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label>Invoice Number</label>
                            <input type="text" class="form-control" id="invoice" name="invoice" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sucess">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>