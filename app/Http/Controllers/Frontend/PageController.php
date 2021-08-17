<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function routePage($route)
    {
        $path = 'frontend/'.$route;
        // dd($path);

        if (view()->exists($path)) {
            return view($path);
        }
        abort(404);
    }
}
