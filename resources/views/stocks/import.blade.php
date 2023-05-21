
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

            <form role="form" action="{{route('import.legacies')}}" method="post" enctype="multipart/form">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label for="exampleInputFile" >File </label>
                        <input type="file" id="file" name="file">
                        <p class="text-danger">{{$errors->first('file')}}</p>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>

                <div class="box-body">
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-warning"></i> Attention! &nbsp;
                    File Type Only (.xls, .xlsx)
                </div>
                </div>
            </form>
        </div>
    </div>
