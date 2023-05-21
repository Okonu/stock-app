
<div class="box box-success">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Warehouse</th>
                            <th>Total Bags</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bagsPerWarehouse as $warehouseId => $totalBags)
                            <tr>
                                <td>{{ $warehouse[$warehouseId] }}</td>
                                <td>{{ $totalBags }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>