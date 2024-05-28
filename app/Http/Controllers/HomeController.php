<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use App\Models\User;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $pins = Pin::where('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('description', 'LIKE', '%' . $keyword . '%')
            ->orWhere('tags', 'LIKE', '%' . $keyword . '%')
            ->inRandomOrder() // Mengacak urutan
            ->get();
        return view('welcome', compact('pins', 'keyword'));
    }

}
