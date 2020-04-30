<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BackendController extends Controller
{
    //


    public function postRequest(Request $request) {
        //dd($request->json()->all());
        /*
         *
                'text' => $request->json()->get('content'),
                'detector' => $request->json()->get('detection'),
                'suggestor' => $request->json()->get('suggestion'),
                'ranker' => $request->json()->get('ranking')
         */
        //echo $request->json()->all();

        //dd($request->json());

        $response = Http::post('http://0.0.0.0:9875/', [
            'name' => 'kakadu',
            'req' => $request->all(),
            'text' => $request->input('content'),
            'detector' => $request->input('detection'),
            'suggestor' => $request->input('suggestion'),
            'ranker' => $request->input('ranking')
        ]);

        return $response->json();
        dd($response->json());
        /*

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'http://0.0.0.0:9876/', [
            'form_params' => [
                'text' => $request->json()->get('content'),
                'detector' => $request->json()->get('detection'),
                'suggestor' => $request->json()->get('suggestion'),
                'ranker' => $request->json()->get('ranking')
            ]
          ]
        );
        $response = $response->getBody()->getContents();
        //print_r($request->json()->all());
        echo '<pre>';
        print_r($response);
        return $response->json();
        */
    }
    public function getRequest() {
        $client = new \GuzzleHttp\Client();
        $request = $client->get('http://0.0.0.0:9875/');
        $response = $request->getBody()->getContents();
        echo '<pre>';
        print_r($response);
        exit;
    }

    public function postBenchmarkRequest(Request $request) {
        $response = Http::post('http://0.0.0.0:9875/bench', [
            'benchmark' => $request->input('benchmark')
        ]);

        return $response->json();
    }
}
