<?php

namespace App\Http\Controllers\Benchmark;

use Illuminate\Http\Request;

class BenchmarkController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {

    }

    public function index() {
        return view('benchmark.index');
    }

    public function show($id) {
        return view('benchmark.show');
    }

    public function create() {

    }

    public function store(Request $request) {

    }

}
