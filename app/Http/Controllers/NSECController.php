<?php

namespace App\Http\Controllers;

use App\Models\NSECBenchmark;
use App\Models\NSECBenchmarkAlignment;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Response;
use \NlpTools\Tokenizers\WhitespaceAndPunctuationTokenizer;

class NSECController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->tokenizer = WhitespaceAndPunctuationTokenizer();
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
        // Receive the names of all benchmarks specific for nsec (internal)
        return view('nsec.benchmark');
    }

    public function addResults() {
        Response::json(Input::get('results'));;
    }

    public function createBenchmark(Request $request) {

        ///TODO(naetherm): Loop through all lines of the uploaded file
        $row = 1;

        $name = $request->input('name');
        $language = $request->input('language');

        /// TODO(naetherm): Create the benchmark entry, if there is already a benchmark with that name, delete that entry first
        $entry = NSECBenchmark::where('name', $name)->take(1)->get();
        if ($entry !== null) {
            // Don't forget to remove all the entries in '*_alignment'

            NSECBenchmarkAlignment::where('benchmark_id', $entry->id)->delete();
            $entry->delete();
        }
        $entry = NSECBenchmark::create([
            'name' => $name,
            'language' => $language
        ]);

        // Cache the ID of the nsec benchmark
        $id = $entry->id;

        if (($handle = $request->file('benchmark_file')->openFile()) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                //$num = count($data);
                $num = 2; // always only two entries
                $row++;

                $src = $this->tokenizer($data[0]);
                $grt = $this->tokenizer($data[1]);

                // Calculate alignment
                $response = Http::post('aligner:6503/v1/aligner/api', [
                    'groundtruth' => $grt,
                    'source' => $src,
                ]);

                // oush results to nsec_benchmark_alignment table
                NSECBenchmarkAlignment::create([
                    'benchmark_id' => $id,
                    'alignment' => $response->json()
                ]);
            }
            fclose($handle);
        }


    }


}
