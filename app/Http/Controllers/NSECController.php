<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NSECController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        return view('nsec.index');
    }

    public function benchmark() {
        return view('nsec.benchmark');
    }
}
