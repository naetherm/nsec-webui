<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

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

    public function addResults() {
        Response::json(Input::get('results'));;
    }
}
