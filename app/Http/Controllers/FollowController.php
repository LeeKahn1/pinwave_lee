<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Following;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function store(User $user)
    {
        if (!Auth::user()->following->contains($user->id)) {
            Auth::user()->following()->attach($user->id);
        }

        return back();
    }

    public function destroy(User $user)
    {
        if (Auth::user()->following->contains($user->id)) {
            Auth::user()->following()->detach($user->id);
        }

        return back();
    }
}
