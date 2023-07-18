@extends('admin.layouts.app')

@section('content')
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <!-- Add Order -->
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Message</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form method="POST" action="{{ route('communicate.message') }}">
                                @csrf
                                <div class="form-group">
                                    <select class="form-control" name="group">
                                        <option value="all">All</option>
                                        <option value="company">Company</option>
                                        <option value="seeker">Seeker</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" rows="9" name="message" id="comment"></textarea>
                                </div>
                                <button class="btn btn-primary btn-block" type="submit">Send</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
