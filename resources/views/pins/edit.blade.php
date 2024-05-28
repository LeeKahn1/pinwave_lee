<h1>Edit Pin</h1>
<form method="POST" action="{{ route('pins.update', $pin) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <label for="title">Title</label>
    <input id="title" type="text" name="title" value="{{ $pin->title }}">
    <label for="description">Description</label>
    <input id="description" type="text" name="description" value="{{ $pin->description }}">
    <label for="image">Image</label>
    <input id="image" type="file" name="image">
    <button type="submit">Update</button>
</form>
