<h1>All Pins</h1>
@foreach ($pins as $pin)
    <div>
        <h2>{{ $pin->title }}</h2>
        <p>{{ $pin->description }}</p>
        <img src="{{ asset('storage/pins/' . $pin->image_path) }}" alt="{{ $pin->title }}">
        <a href="{{ route('pins.show', $pin) }}">View</a>
        <a href="{{ route('pins.edit', $pin) }}">Edit</a>
    </div>
@endforeach
