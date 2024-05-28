<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Pin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Pin $pin)
    {
        $request->validate([
            'content' => 'required',
        ]);

        $comment = new Comment;
        $comment->user_id = Auth::id();
        $comment->pin_id = $pin->id;
        $comment->content = $request->content;
        $comment->save();

        return back();
    }
}
