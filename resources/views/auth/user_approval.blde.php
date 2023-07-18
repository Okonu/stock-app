<!-- Example code for the view -->
@foreach ($users as $user)
    <div>
        <p>{{ $user->name }}</p>
        <p>{{ $user->email }}</p>
        <!-- Display approve and disapprove buttons based on the user's approval status -->
        @if (!$user->approved)
            <form action="{{ route('approveUser', ['userId' => $user->id]) }}" method="post">
                @csrf
                <input type="hidden" name="approve" value="1">
                <button type="submit">Approve</button>
            </form>
        @else
            <form action="{{ route('approveUser', ['userId' => $user->id]) }}" method="post">
                @csrf
                <input type="hidden" name="approve" value="0">
                <button type="submit">Disapprove</button>
            </form>
        @endif
    </div>
@endforeach
