@extends('layouts.layout')

@section('modal')
    <div id="progress_modal" class="circular-modal" hidden>
        <div class="circle"></div>
        <div class="circle-small"></div>
        <div class="circle-big"></div>
        <div class="circle-inner-inner"></div>
        <div class="circle-inner"></div>
    </div>
@endsection()

@section('featured')
    <div class="container">
        <div class="hero-inner">
            <h1 class="hero-title h2-mobile mt-0 is-revealing">NSEC - Evaluation Benchmark</h1>
            <p class="hero-paragraph is-revealing">
                The internal used routines for the quality evaluation of certain changes made to the core.<br>
                Select the benchmark to perform the internal testing on and click <strong>Bench</strong>.
            </p>
        </div>
    </div>
@endsection()

@section('content')
    <div class="container">
        <div class="benchmark"><span>Benchmark</span></div>
        <div class="container">
            <div class="inline-input">
                <label class="col-md-4 control-label" for="benchmark"><strong>Benchmark</strong></label>
                <div class="col-md-4">
                    <select id="benchmark" name="benchmarketection" class="form-control form-control-xs">
                        <option value="demo">Demo</option>
                        <option value="en_tiny">Tiny English</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row float-right">
            <div class="col-md-12">
                <button type="submit" id="bench" formaction="index" class="button button-primary button-block button-shadow button-correct">Bench</button>
            </div>
        </div>
    </div>

    <div class="container result-container">
        <div class="row">
            <div class="col-2">
                <input type="text" value="0" data-height="100" data-width="100" readonly="readonly" id="correct_sentences">
                <div class="knob-label">Correct Sentences (%)</div>
            </div>
            <div class="col-2">
                <input type="text" value="0" data-height="100" data-width="100" readonly="readonly" id="correct_tokens">
                <div class="knob-label">Correct Tokens (%)</div>
            </div>
        </div>
    </div>
@endsection()

@section('extra_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
    <script src="{{ asset('jquery-knob/jquery.knob.min.js') }}"></script>
    <script type="text/javascript">
        $(".result-container").fadeOut('fast');
        $("#correct_tokens").knob({'min':0, 'max':100});
        $("#correct_sentences").knob({'min':0, 'max':100});
        $("#bench").click(function(e) {
            var editor_content = {
                'benchmark': $('#benchmark').val(),
            };
            // Show modal
            $("#progress_modal").fadeIn('slow');
            $("#content-container").addClass("background-blur", 500);

            $.ajax({
                url: 'postBenchmark',
                type: 'GET',
                dataType: 'json',
                data: editor_content,   // converts js value to JSON string
                contentType:"application/json"
            })
                .done(function(result){     // on success get the return object from server
                    console.log(result)     // do whatever with it. In this case see it in console
                    // Remove modal again
                    $("#progress_modal").fadeOut('fast');
                    $(".result-container").fadeIn('slow');
                    $("#content-container").removeClass("background-blur", 500);
                    // Update timings
                    //console.log("Received the following text:");
                    //console.log(result["text"]);
                    $('#correct_tokens')
                        .val((result["results"]["correct_tokens"] / result["results"]["num_tokens"])*100)
                        .trigger('change');
                    $('#correct_sentences')
                        .val(result["results"]["sentence_accuracy"]*100)
                        .trigger('change');
                })
        });
    </script>
@endsection()
