<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Page::active()->where('slug', $slug)->firstOrFail();
        return view('pages.dynamic-pages', compact('page'));
    }
}
