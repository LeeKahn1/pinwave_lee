<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use App\Models\Album;

use Illuminate\Http\Request;

class PinController extends Controller
{
    public function index()
    {
        $pins = Pin::all();
        return view('pins.index', compact('pins'));
    }

    public function create()
    {
        return view('pins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'album_id' => 'nullable|exists:albums,id', // validasi untuk album_id
        ]);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->storeAs('public/pins', $imageName);

        $pin = new Pin;
        $pin->fill($request->all());
        $pin->user_id = auth()->id();
        $pin->image_path = $imageName;
        $pin->save();

        if ($request->has('album_id')) {
            $album = Album::find($request->album_id);
            if ($album && !$album->pins->contains($pin)) {
                $album->pins()->attach($pin);
            }
        }

        return redirect('/');
    }

    public function show(Pin $pin)
    {
        $otherPins = Pin::where('user_id', $pin->user_id)
            ->where('id', '!=', $pin->id)
            ->get();

        return view('pins.show', compact('pin', 'otherPins'));
    }

    public function edit(Pin $pin)
    {
        return view('pins.edit', compact('pin'));
    }

    public function update(Request $request, Pin $pin)
    {
        $pin->update($request->all());
        return redirect()->route('pins.index');
    }

    public function destroy(Pin $pin)
    {
        $pin->delete();
        return redirect()->route('pins.index');
    }
}
