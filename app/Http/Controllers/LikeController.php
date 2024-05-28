<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use App\Notifications\PinLiked;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(Request $request, Pin $pin)
    {
        $like = $pin->likes()->firstOrCreate([
            'user_id' => Auth::id(),
        ]);

        // Get the owner of the pin
        $owner = User::find($pin->user_id);

        // Notify the owner
        $owner->notify(new PinLiked($pin, $like->user_id));

        return back();
    }

    public function destroy(Pin $pin)
    {
        $pin->likes()->where('user_id', Auth::id())->delete();

        return back();
    }
}
