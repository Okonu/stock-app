@extends('layouts.master')

@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- Bootstrap Tooltip -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap-tooltip.css') }}">
@endsection

@section('content')
    <div class="box box-success">

        <div class="box-header">
            <h3 class="box-title">Reconciliation Page</h3>
        </div>

        <div class="box-body">
            <a href="{{ route('reconciliation.index') }}" class="btn btn-primary">Perform Reconciliation</a>
            
            <h2>Quantity Mismatches</h2>
            @if(count($quantityMismatches) > 0)
                <table class="table table-bordered table-hover table-striped" id="quantity-mismatches-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Invoice Number</th>
                            <th>Current Quantity</th>
                            <th>Physical Quantity</th>
                            <th>Status</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quantityMismatches as $index => $mismatch)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $mismatch->invoice }}</td>
                                <td>{{ $mismatch->current_qty }}</td>
                                <td>{{ $mismatch->physical_qty }}</td>
                                <td>
                                    <span class="label label-danger">Mismatch</span>
                                </td>
                                <td data-toggle="tooltip" data-placement="top" title="{{ $mismatch->comment }}">
                                    <span class="comment-tooltip">{{ $mismatch->comment }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No quantity mismatches found.</p>
            @endif

            <h2>Missing Invoices</h2>
            @if(count($missingInvoices) > 0)
                <table class="table table-bordered table-hover table-striped" id="missing-invoices-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Invoice Number</th>
                            <th>Missing From</th>
                            <th>Status</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <!--<tbody>-->
                    <!--    @foreach($missingInvoices as $index => $missingInvoice)-->
                    <!--        <tr>-->
                    <!--            <td>{{ $index + 1 }}</td>-->
                    <!--            <td>{{ $missingInvoice->invoice }}</td>-->
                    <!--            <td>-->
                    <!--                @if($missingInvoice->missing_from_table === 'legacies')-->
                    <!--                    System Stock-->
                    <!--                @elseif($missingInvoice->missing_from_table === 'stocks')-->
                    <!--                    Physical Stock-->
                    <!--                @endif-->
                    <!--            </td>-->
                    <!--            <td>-->
                    <!--                @if($missingInvoice->missing_from_table === 'legacies')-->
                    <!--                    <span class="label label-danger">Mismatch</span>-->
                    <!--                @elseif($missingInvoice->missing_from_table === 'stocks')-->
                    <!--                    <span class="label label-danger">Mismatch</span>-->
                    <!--                @endif-->
                    <!--            </td>-->
                    <!--            <td data-toggle="tooltip" data-placement="top" title="{{ $missingInvoice->comment }}">-->
                    <!--                <span class="comment-tooltip">{{ $missingInvoice->comment }}</span>-->
                    <!--            </td>-->
                    <!--        </tr>-->
                    <!--    @endforeach-->
                    <!--</tbody>-->
                    <tbody>
                        @foreach($missingInvoices as $index => $missingInvoice)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $missingInvoice->invoice }}</td>
                                <td>
                                    @if($missingInvoice->missing_from_table === 'legacies')
                                        System Stock
                                    @elseif($missingInvoice->missing_from_table === 'stocks')
                                        Physical Stock
                                    @endif
                                </td>
                                <td>
                                    @if($missingInvoice->missing_from_table === 'legacies' && $missingInvoice->missing_from_table === 'stocks')
                                        <span class="label label-danger">Mismatch</span>
                                    @elseif($missingInvoice->missing_from_table === 'legacies' || $missingInvoice->missing_from_table === 'stocks')
                                        <span class="label label-success">Match</span>
                                    @endif
                                </td>
                                <td>
                                    @if($missingInvoice->missing_from_table === 'legacies' && $missingInvoice->missing_from_table === 'stocks')
                                        <span class="comment-tooltip" data-toggle="tooltip" data-placement="top" title="Invoice missing in both tables">
                                            Invoice missing in both tables
                                        </span>
                                    @elseif($missingInvoice->missing_from_table === 'legacies' || $missingInvoice->missing_from_table === 'stocks')
                                        <span class="comment-tooltip" data-toggle="tooltip" data-placement="top" title="{{ $missingInvoice->comment }}">
                                            Invoice exists in both tables
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            @else
                <p>No missing invoices found.</p>
            @endif

            
        </div>
    </div>

@endsection

@section('bot')
    <!-- jQuery -->
    <script src="{{ asset('assets/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- Bootstrap Tooltip -->
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap-tooltip.js') }}"></script>

    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#missing-invoices-table').DataTable();
            $('#quantity-mismatches-table').DataTable();
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Rest of your script code...
        // ...
    </script>
@endsection
