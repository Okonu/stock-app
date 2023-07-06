<div class="container">
        <h1>Producers Stock Count</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Warehouse</th>
                    <th>Owners</th>
                    <th>Gardens</th>
                    <th>Total Quantity</th>
                    <th>Bags per Bay</th>
                    <th>Stock Dates</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($ownersCountPerWarehouse as $ownersCount)
                <tr>
                    <td>{{ $warehouse[$ownersCount->warehouse_id] }}</td>
                    <td>{{ $ownersCount->owners }}</td>
                    <td>{{ $ownersCount->gardens }}</td>
                    <td>{{ $bagsPerWarehouse[$ownersCount->warehouse_id] }}</td>
                    <td>{{ $bagsPerBay[$ownersCount->warehouse_id] }}</td>
                    <td>{{ $stockDates[$ownersCount->warehouse_id] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
