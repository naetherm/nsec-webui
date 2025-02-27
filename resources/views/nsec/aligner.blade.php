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
        <div class="benchmark"><span>Benchmark Aligner</span></div>
        
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <input id="source" name="source" type="text" value="">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <input id="groundtruth" name="groundtruth" type="text" value="">
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
                        .val(result["results"]["token_accuracy"])
                        .trigger('change');
                    $('#correct_sentences')
                        .val(result["results"]["sentence_accuracy"])
                        .trigger('change');

                    // Write all grt <-> prediction pairs to erroneous_sentences
                    var grt_prd_pairs = "";
                    $.each(result["articles"], function(aidx, article) {
                        $.each(article["sentences"], function(sidx, sentence) {
                            grt_prd_pairs += "<p class=\"result\"><b>" + sentence["groundtruth"] + "</b></p><br/>";
                            grt_prd_pairs += "<p class=\"result\"><i>" + sentence["prediction"]["text"] + "</i></p><br/>";
                        })
                    })
                    $("#erroneous_sentences").html(grt_prd_pairs);
                })
        });
    </script>
@endsection()
