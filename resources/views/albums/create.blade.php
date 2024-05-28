<h1>Create Album</h1>
<form method="POST" action="{{ route('albums.store') }}">
    @csrf
    <label for="name">Name</label>
    <input id="name" name="name" type="text" required>
    <button type="submit">Create</button>
</form>
