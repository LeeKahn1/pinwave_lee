<h1>Edit Album</h1>
<form method="POST" action="{{ route('albums.update', $album) }}">
    @csrf
    @method('PUT')
    <label for="name">Name</label>
    <input id="name" name="name" type="text" value="{{ $album->name }}" required>
    <button type="submit">Update</button>
</form>
