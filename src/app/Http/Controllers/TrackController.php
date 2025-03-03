<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackController extends Controller
{
	public function js(): View
    {
        return view('track.js');
	}
}
