@extends('layouts.master')

@section('top')
@endsection


@section('content')
<!--(Stat box) -->
<div class="row">
        <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ \App\Legacy::count() }}<sup style="font-size: 20px"></sup></h3>

<<<<<<< HEAD
                        <p>Physical Entries</p>
=======
                        <p>Current Stock</p>
>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
                    </div>
                    <div class="icon">
                        <i class="fa fa-list"></i>
                    </div>
                    <a href="{{ route('stocks.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
<<<<<<< HEAD
                        <h3>{{ \App\Legacy::count() }}<sup style="font-size: 20px"></sup></h3>

                        <p>System Entries</p>
=======
                        <h3>{{ $totalMismatchQty }}<sup style="font-size: 20px"></sup></h3>

                        <p>Inconsistencies</p>
>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
                    </div>
                    <div class="icon">
                        <i class="fa fa-list"></i>
                    </div>
                    <a href="{{ route('stocks.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- ./col -->

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ \App\Owner::count() }}<sup style="font-size: 20px"></sup></h3>

                        <p>Producers</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-list"></i>
                    </div>
                    <a href="{{ route('owners.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $totalBags }}<sup style="font-size: 20px"></sup></h3>

                        <p>Total Bags</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-list"></i>
                    </div>
                    <a href="{{ route('warehouses.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
</div>
<a href="{{ route('stocks.reports') }}" class="btn btn-primary">View Stock Reports</a>

@include('stocks.tableu')

@include('stocks.bags', ['warehouse' => $warehouse, 'bagsPerWarehouse' => $bagsPerWarehouse])

@endsection

@section('top')
@endsection
