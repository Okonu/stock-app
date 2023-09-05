{{-- resources/views/stocks/stockAllExcel.blade.php --}}

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css ') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/Ionicons/css/ionicons.min.css') }}">

    <title>Stock Taken Exports All Excel</title>
</head>
<body>
<style>
    #stock {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #stock td, #stock th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #stock tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #stock tr:hover {
        background-color: #ddd;
    }

    #stock th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }
</style>

<table id="stock" width="100%">
    <thead>
    <tr>
        <th>Taken By</th>
        <th>Date Taken</th> 
        <th>Warehouse</th>
        <th>Bays</th>
        <th>Producer</th>
        <th>Garden</th>
        <th>Grade</th>
        <th>Package Type</th>
        <th>Invoice</th>
        <th>Package No.</th>
        <th>Production Year</th>
        <th>Remarks</th>
    </tr>
    </thead>
    @foreach($stock as $p)
        <tbody>
        <tr>
            <!-- <td>{{$p->id}}</td> -->
            <td>{{$p->user->name}}</td>
            <td>{{$p->created_at ? $p->created_at->format('Y-m-d') : '-' }}</td> 
            <td>{{$p->warehouse->name}}</td>
            <td>{{$p->bays->name}}</td>
            <td>{{$p->owner->name}}</td>
            <td>{{$p->garden->name}}</td>
            <td>{{$p->grade->name}}</td>
            <td>{{$p->package->name}}</td>
            <td>{{$p->invoice}}</td>
            <td>{{$p->qty}}</td>
            <td>{{$p->year}}</td>
            <td>{{$p->remark}}</td>
        </tr>
        </tbody>
    @endforeach

</table>
</body>
</html>
