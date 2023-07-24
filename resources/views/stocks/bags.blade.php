
<div class="box box-success">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Warehouse</th>
                            <th>Total Bags</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bagsPerWarehouse as $warehouse_id => $totalBags)
                            <tr>
                                <td>{{ $warehouse[$warehouse_id] }}</td>
                                <td>{{ $totalBags }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>