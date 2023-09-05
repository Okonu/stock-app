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
                        <h3>{{ \App\Stock::count() }}<sup style="font-size: 20px"></sup></h3>

                        <p>Physical Entries</p>
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
                        <h3>{{ \App\Legacy::count() }}<sup style="font-size: 20px"></sup></h3>

                        <p>System Entries</p>
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

@include('stocks.tableu')

@include('stocks.bags', ['warehouse' => $warehouse, 'bagsPerWarehouse' => $bagsPerWarehouse])

@endsection

@section('top')
@endsection
