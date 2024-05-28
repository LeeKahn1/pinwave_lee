<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Pin;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function index()
    {
        $albums = Album::where('user_id', auth()->id())->get();
        return view('albums.index', compact('albums'));
    }

    public function create()
    {
        return view('albums.create');
    }

    public function store(Request $request)
    {
        $album = Album::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
        ]);

        return back();
    }

    public function show(Album $album)
    {
        return view('albums.show', compact('album'));
    }

    public function edit(Album $album)
    {
        return view('albums.edit', compact('album'));
    }

    public function update(Request $request, Album $album)
    {
        $album->update($request->only('name'));
        return redirect()->route('albums.index');
    }

    public function destroy(Album $album)
    {
        $album->delete();
        return redirect()->route('albums.index');
    }

    public function addPin(Request $request, Album $album)
    {
        $pin = Pin::find($request->pin_id);
        if (!$album->pins->contains($pin)) {
            $album->pins()->attach($pin);
        }
        return back();
    }

    public function removePin(Request $request, Album $album)
    {
        $pin = Pin::find($request->pin_id);
        $album->pins()->detach($pin);
        return back();
    }

    public function saveToAlbum(Request $request, $albumId)
    {
        // Get the album
        $album = Album::find($albumId);

        // Check if the album belongs to the current user
        if ($album->user_id != auth()->id()) {
            return redirect()->back()->with('error', 'You do not have permission to add to this album.');
        }

        // Get the pin
        $pin = Pin::find($request->pin_id);

        // Check if the pin exists and is not already in the album
        if ($pin && !$album->pins->contains($pin)) {
            // Attach the pin to the album
            $album->pins()->attach($pin);
        }

        return redirect()->back()->with('success', 'Pin saved to album successfully.');
    }
}
