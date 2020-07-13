<?php

namespace App\Http\Controllers;

use App\Models\NSECBenchmark;
use App\Models\NSECBenchmarkAlignment;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Response;
use \NlpTools\Tokenizers\WhitespaceAndPunctuationTokenizer;

class ResultContainer {
    public $detection_precision = 0.0;
    public $detection_recall = 0.0;
    public $detection_fscore = 0.0;

    public $correction_precision = 0.0;
    public $correction_recall = 0.0;
    public $correction_fscore = 0.0;

    public $num_sentences = 0;
    public $num_tokens = 0;
    public $num_erroneous_tokens = 0;
    public $num_valid_tokens = 0;
}

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

    public function benchmark(Request $request) {
        // Receive the names of all benchmarks specific for nsec (internal)
        //$benchmarks = NSECBenchmark::all();
        $benchmarks = null;

        return view('nsec.benchmark', [
            'benchmarks' => $benchmarks
        ]);
    }

    public function evaluateBenchmark(Request $request) {
        // Retrieve the benchmark
        $benchmark_name = $request->input('benchmark');
        $benchmark = NSECBenchmark::where('name', $benchmark_name)->firstOrFail();

        // Cached settings
        $detector = $request->input('detection');
        $suggestor = $request->input('suggestion');
        $ranker = $request->input('ranking');

        // Retrieve the alignments of the associated (internal) benchmark
        $benchmark_alignments = NSECBenchmarkAlignment::where('benchmark_id', $benchmark->id)->get();

        $benchmark_results = new ResultContainer();
        $benchmark_results->num_sentences = $benchmark_alignments->count();

        foreach ($benchmark_alignments as $alignment) {
            // TODO(naetherm): Evaluate this @home
            $benchmark_results->num_tokens += $alignment->groundtruth_tokens->count();
            $backend_request = new Request();
            $backend_request->replace([
                'content' => $alignment->source,
                'detector' => $detector,
                'suggestor' => $suggestor,
                'ranker' => $ranker
            ]);
            $results = $this->backendCaller($backend_request);

        }

        return view('nsec.benchmark_evaluation', [
            'benchmark' => $benchmark
        ]);
    }

    public function addResults() {
        Response::json(Input::get('results'));
    }

    public function addBenchmark() {
        return view('nsec.create_benchmark');
    }

    public function createBenchmark(Request $request) {

        ///TODO(naetherm): Loop through all lines of the uploaded file
        $row = 1;
        $tokenizer = new \NlpTools\Tokenizers\WhitespaceAndPunctuationTokenizer();

        dd($request);

        $name = $request->input('name');
        $language = $request->input('language');

        /// TODO(naetherm): Create the benchmark entry, if there is already a benchmark with that name, delete that entry first
        $entry = NSECBenchmark::where('name', $name)->firstOrFail();
        if ($entry) {
            // Don't forget to remove all the entries in '*_alignment'
            //dd($entry);

            NSECBenchmarkAlignment::where('benchmark_id', $entry->id)->delete();
            $entry->delete();
        }
        $entry = NSECBenchmark::create([
            'name' => $name,
            'language' => $language
        ]);

        // Cache the ID of the nsec benchmark
        $id = $entry->id;

        if (($handle = fopen($request->file('benchmark_file')->getRealPath(), 'r')) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                //$num = count($data);
                $num = 2; // always only two entries
                $row++;

                $src = $tokenizer->tokenize($data[0]);
                $grt = $tokenizer->tokenize($data[1]);

                // Calculate alignment
                $response = Http::post('aligner:6503/v1/aligner/api', [
                    'groundtruth' => $data[1],
                    'source' => $data[0],
                    'groundtruth_tokens' => $grt,
                    'source_tokens' => $src
                ]);

                // Push results to nsec_benchmark_alignment table
                NSECBenchmarkAlignment::create([
                    'benchmark_id' => $id,
                    'alignment' => $response->json(),
                    'groundtruth' => $data[1],
                    'source' => $data[0],
                    'groundtruth_tokens' => $grt,
                    'source_tokens' => $src
                ]);
            }
            fclose($handle);
        }

        return view('nsec.index');
    }



    public function backendCaller(Request $request) {
        // Call the backend
        $response = Http::post('nsec:9876/', [
            'text' => $request->input('sentence'),
            'detector' => $request->input('detection'),
            'suggestor' => $request->input('suggestion'),
            'ranker' => $request->input('ranking')
        ]);

        // return the evaluation
        return $response->json();
    }

}
