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
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ \App\Bay::count() }}</h3>
                <p>Bays</p>
            </div>
            <div class="icon">
                <i class="fa fa-cubes"></i>
            </div>
            <a href="{{ route('bays.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ \App\Owner::count() }}</h3>

                <p>Farm Owners</p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            <a href="{{ route('owners.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>

@include('stocks.tableu')
@endsection

@section('top')
@endsection
