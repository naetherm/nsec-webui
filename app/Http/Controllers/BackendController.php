<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BackendController extends Controller
{
    //


    public function postRequest(Request $request) {
        $response = Http::post('nsec:9876/', [
            'text' => $request->input('content'),
            'detector' => $request->input('detection'),
            'suggestor' => $request->input('suggestion'),
            'ranker' => $request->input('ranking')
        ]);

        return $response->json();
    }
    public function getRequest() {
        $client = new Client();
        $request = $client->get('nsec:9876/');
        $response = $request->getBody()->getContents();
        echo '<pre>';
        print_r($response);
        exit;
    }

    public function postBenchmarkRequest(Request $request) {
        $response = Http::post('nsec:9876/bench', [
            'benchmark' => $request->input('benchmark')
        ]);

        return $response->json();
    }
}
