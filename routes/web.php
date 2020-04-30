<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

// NSEC Frontend
Route::get('/', [
    'as' => 'nsec.index',
    'uses' => 'NSECController@index']
);
// NSEC-Evaluation Frontend
Route::get('/spell_bench', [
    'as' => 'nsec.spell_bench',
    'uses' => 'NSECController@benchmark'
]);

// NSEC Backend communication
Route::get('post','BackendController@postRequest');
Route::get('get','BackendController@getRequest');
Route::get('postBenchmark','BackendController@postBenchmarkRequest');

//Route::get('/home', 'HomeController@index')->name('home');

// NSEC-Benchmark Frontend
Route::get('/benchmarks', 'Benchmark/BenchmarkController@index')->name('nsec.benchmarks');

Route::get('/benchmarks/benchmark/${id}-{slug?}', 'Benchmark/BenchmarkController@show')->name('nsec.benchmark');
Route::get('/benchmarks/program/${id}-{slug?}', 'Benchmark/BenchmarkController@show')->name('nsec.program');
