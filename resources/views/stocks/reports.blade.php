
<form action="{{ route('stocks.index') }}" method="GET">
    <div class="form-group">
        <label for="warehouse_id">Select Warehouse:</label>
        <select name="warehouse_id" id="warehouse_id" class="form-control">
            <option value="">All Warehouses</option>
            @foreach($warehouse as $id => $name)
                <option value="{{ $id }}" {{ $id == $warehouse_id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Filter</button>
</form>

@if($monthlyReports->isNotEmpty())
    <table class="table">
        <thead>
            <tr>
                <th>Month</th>
                <th>Owner</th>
                <th>Garden</th>
                <th>Total Bags</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyReports as $monthlyReport)
                <tr>
                    <td>{{ $monthlyReport['month'] }}</td>
                    @foreach($monthlyReport['data'] as $data)
                    <td>{{ $data->owner_id }}</td>
                        <td>{{ $data->garden_id }}</td>
                        <td>{{ $data->total_bags }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
@else
    <p>No monthly reports available.</p>
@endif
