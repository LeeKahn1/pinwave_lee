<h1>{{ $album->name }}</h1>
<h2>Pins</h2>
@foreach ($album->pins as $pin)
    <div>
        <h3>{{ $pin->title }}</h3>
        <img class="w-full h-auto object-cover rounded-lg max-h-[600px]"
            src="{{ asset('storage/pins/' . $pin->image_path) }}" alt="Image Description">
        <form method="POST" action="{{ route('albums.removePin', $album) }}">
            @csrf
            <input type="hidden" name="pin_id" value="{{ $pin->id }}">
            <button type="submit">Remove from album</button>
        </form>
    </div>
@endforeach
<form method="POST" action="{{ route('albums.addPin', $album) }}">
    @csrf
    <label for="pin_id">Add pin</label>
    <select id="pin_id" name="pin_id">
        @foreach (App\Models\Pin::all() as $pin)
            <option value="{{ $pin->id }}">{{ $pin->title }}</option>
        @endforeach
    </select>
    <button type="submit">Add to album</button>
</form>
