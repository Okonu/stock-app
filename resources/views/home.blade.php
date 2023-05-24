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

                        <p>Stock Taken</p>
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
                        <h3>{{ \App\Warehouse::count() }}<sup style="font-size: 20px"></sup></h3>

                        <p>Warehouse</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-list"></i>
                    </div>
                    <a href="{{ route('warehouses.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- ./col -->
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3>Total Bags</h3>
                            <!-- small box -->
                        <div class="small-box bg-red">
                            <div class="inner">
                                <p>Total Bags: {{ $totalBags }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</div>

@include('stocks.tableu')

@include('stocks.bags', ['warehouse' => $warehouse, 'bagsPerWarehouse' => $bagsPerWarehouse])




@endsection

@section('top')
@endsection
