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
            <h1 class="hero-title h2-mobile mt-0 is-revealing">NSEC - Neural Spelling Error Correction</h1>
            <p class="hero-paragraph is-revealing">
                The online demo of our implementation of a convolutional encoder decoder driven neural network for the task of <b>spelling error correction</b>. Just type in the text on the left side, click Correct and see the suggested correction on the right side of the window.
            </p>
        </div>
    </div>
@endsection()

@section('content')
    <div class="container text-left">
        <div class="header"><span>Settings</span>

        </div>
        <div class="content" style="display: none">
            <div class="container">
                <div class="inline-input">
                    <label class="col-md-4 control-label" for="detection"><strong>Detection Method</strong></label>
                    <div class="col-md-4">
                        <select id="detection" name="detection" class="form-control form-control-xs">
                            <option value="wcp">WCP Detect</option>
                        </select>
                    </div>
                </div>
                <div class="inline-input">
                    <label class="col-md-4 control-label" for="detection"><strong>Suggestion Method</strong></label>
                    <div class="col-md-4">
                        <select id="suggestion" name="suggestion" class="form-control form-control-xs">
                            <option value="norvig">Norvig</option>
                            <option value="passthrough">Passthrough</option>
                        </select>
                    </div>
                </div>
                <div class="inline-input">
                    <label class="col-md-4 control-label" for="detection"><strong>Ranking Method</strong></label>
                    <div class="col-md-4">
                        <select id="ranking" name="ranking" class="form-control form-control-xs">
                            <option value="bert_accurate">Bert Accurate</option>
                            <option value="bert_fast">Bert Fast (Currently Not Working)</option>
                            <option value="xlnet">XLNet (Currently Not Working)</option>
                            <option value="nmt">NMT (Currently Not Working)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <!-- Create the toolbar container -->
        <div id="toolbar">
            <button class="ql-bold">Bold</button>
            <button class="ql-italic">Italic</button>
        </div>

        <!-- Create the editor container -->
        <div id="editor">
            <p></p>
        </div>
    </div>

    <div id="timers" class="container">
        <strong id="preprocessing_time" class="badge-xs badge-danger"></strong>
        <strong id="detection_time" class="badge-xs badge-danger"></strong>
        <strong id="suggestion_time" class="badge-xs badge-danger"></strong>
        <strong id="ranking_time" class="badge-xs badge-danger"></strong>
    </div>

    <div class="container">
        <div class="row float-left">
            <strong id="backend_health" class="badge badge-danger">Backend Statues</strong>
        </div>
        <div class="row float-right">
            <div class="col-md-12">
                <button type="submit" id="correct" formaction="index" class="button button-primary button-block button-shadow button-correct">Correct</button>
            </div>
        </div>
    </div>
@endsection()

@section('extra_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
    <script type="text/javascript">
        var editor = new Quill('#editor', {
            placeholder: 'Start typing and press the correct button below ...',
            modules: { toolbar: '#toolbar'},
            theme: 'snow'
        });
        setInterval(function() {
            $(document).ready(function() {
                const Url ='http://0.0.0.0:9876/is_healthy';
                $.ajax({
                    url: Url,
                    dataType: 'json',
                    success: function( data ) {
                        $("#backend_health").removeClass("badge-danger").addClass("badge-success");
                        $("#backend_health").text("Online");
                    },
                    error: function( data ) {
                        $("#backend_health").removeClass("badge-success").addClass("badge-danger");
                        $("#backend_health").text("Offline");
                    }
                });
            })
        }, 10000);
        $(".header").click(function () {

            $header = $(this);
            //getting the next element
            $content = $header.next();
            //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
            $content.slideToggle(500, function () {
                //execute this after slideToggle is done
                //change text of header based on visibility of content div
                $header.text(function () {
                    //change text based on condition
                    return $content.is(":visible") ? "Settings" : "Settings";
                });
            });

        });
        $("#correct").click(function(e) {
            var editor_content = {
                'ranking': $('#ranking').val(),
                'suggestion': $('#suggestion').val(),
                'detection': $('#detection').val(),
                'content': editor.getText()
            };
            $("#progress_modal").fadeIn('slow');
            $("#content-container").addClass("background-blur", 500);
            console.log(editor.getText());
            $.ajax({
                url: 'post',
                type: 'GET',
                dataType: 'json',
                data: editor_content,   // converts js value to JSON string
                contentType:"application/json"
            })
                .done(function(result){     // on success get the return object from server
                    console.log(result)     // do whatever with it. In this case see it in console
                    $("#progress_modal").fadeOut('fast');
                    $("#content-container").removeClass("background-blur", 500);
                    // Update timings
                    $('#preprocessing_time').text("Preprocessing: " + result["timing"]["preprocessor"]);
                    $('#detection_time').text("Detection: " + result["timing"]["detector"]);
                    $('#suggestion_time').text("Suggestion: " + result["timing"]["suggestor"]);
                    $('#ranking_time').text("Ranking: " + result["timing"]["ranker"]);

                    /// TODO(naetherm): Use all the information of the full json to create informative output
                    var text = "";

                    $.each (result["sequences"], function(index, value) {
                        var temp = value["text"];
                        var tokens = [];
                        $.each(value["ranking"][0]["info"], function(tidx, tv) {
                            tokens.push(tv);
                        });
                        //tokens.reverse();
                        var n = tokens.length;
                        $.each(tokens, function(ridx, tv) {
                            if (ridx == 0) {
                                text += temp.substring(0, tv["pos"]) + "<strong class\"badge-xs badge-token\">" + tv["suggestion"] + "</strong> ";
                                if (tokens.length == 1) {
                                    text += temp.substring(tv["pos"]+tv["length"], temp.length);
                                }
                            } else if (ridx == (n - 1)) {
                                text += temp.substring(tokens[ridx-1]["pos"]+tokens[ridx-1]["length"], tv["pos"]) + "<strong class\"badge-xs badge-token\">" + tv["suggestion"] + "</strong> " + temp.substring(tv["pos"]+tv["length"], temp.length);
                            } else {
                                text += temp.substring(tokens[ridx-1]["pos"]+tokens[ridx-1]["length"], tv["pos"]) + "<strong class\"badge-xs badge-token\">" + tv["suggestion"] + "</strong> ";
                            }
                        })

                        //text += value["ranking"][0]["correction"];
                        //text += " ";
                    });
                    //editor.clipboard.dangerouslyPasteHTML(text);
                    editor.pasteHTML(text);
                })
        });
    </script>
@endsection()
