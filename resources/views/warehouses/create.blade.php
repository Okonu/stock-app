    <div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="create-warehouse-form" method="POST" action="{{ route('warehouses.store') }}"
                    class="form-horizontal" data-toggle="validator" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('POST') }}

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h3 class="modal-title"></h3>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="id" name="id">

                        <div class="box-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" autofocus required>
                                <span class="help-block with-errors"></span>
                            </div>

                            <div class="form-group" id="bay-container">
                                <label for="bays">Bays</label>
                                <div class="input-group m-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-danger delete-bay" type="button">
                                            Delete
                                        </button>
                                    </div>
                                    <input type="text" class="form-control m-input" name="bays[]"
                                        placeholder="Enter bay name" required>
                                </div>
                            </div>
                            <button type="button" id="add-bay-btn" class="btn btn-primary">Add Another Bay</button>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            function addBayInput() {
                const bayInput = `
                <div class="input-group m-3">
                    <div class="input-group-prepend">
                        <button class="btn btn-danger delete-bay" type="button">Delete</button>
                    </div>
                    <input type="text" class="form-control m-input" name="bays[]" placeholder="Enter bay name" required>
                </div>`;
                $('#bay-container').append(bayInput);
            }

            $(document).on('click', '#add-bay-btn', function () {
                addBayInput();
            });

            $(document).on('click', '.delete-bay', function () {
                $(this).parents(".input-group").remove();
            });
        });
    </script>