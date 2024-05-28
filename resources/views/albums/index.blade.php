=
<h1>Albums</h1>
@foreach ($albums as $album)
    <div>
        <h2>{{ $album->name }}</h2>
        <a href="{{ route('albums.show', $album) }}">View</a>
        <a href="{{ route('albums.edit', $album) }}">Edit</a>
        <form method="POST" action="{{ route('albums.destroy', $album) }}">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </div>
@endforeach
<a href="{{ route('albums.create') }}">Create new album</a>
