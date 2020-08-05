<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NSEC - Neural Spelling Error Correction</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:400,400i|Roboto:500" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/scrollreveal@4.0.0/dist/scrollreveal.min.js"></script>
    <link href="https://cdn.quilljs.com/1.0.0/quill.snow.css" rel="stylesheet">
</head>
<body class="is-boxed has-animations">
    @yield('modal')
    <div id="content-container" class="body-wrap boxed-container">
        <header class="site-header">
            <div class="container">
                <div class="site-header-inner">
                    <div class="brand header-brand">
                        <h1 class="m-0">
                            <a href="#">
                                <svg width="32" height="32" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                    <title>NSEC - Neural Spelling Error Correction</title>
                                    <defs>
                                        <linearGradient x1="100%" y1="0%" x2="0%" y2="100%" id="logo-gradient-b">
                                            <stop stop-color="#39D8C8" offset="0%"/>
                                            <stop stop-color="#BCE4F4" offset="47.211%"/>
                                            <stop stop-color="#838DEA" offset="100%"/>
                                        </linearGradient>
                                        <path d="M32 16H16v16H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h28a2 2 0 0 1 2 2v14z" id="logo-gradient-a"/>
                                        <linearGradient x1="23.065%" y1="25.446%" y2="100%" id="logo-gradient-c">
                                            <stop stop-color="#1274ED" stop-opacity="0" offset="0%"/>
                                            <stop stop-color="#1274ED" offset="100%"/>
                                        </linearGradient>
                                    </defs>
                                    <g fill="none" fill-rule="evenodd">
                                        <mask id="logo-gradient-d" fill="#fff">
                                            <use xlink:href="#logo-gradient-a"/>
                                        </mask>
                                        <use fill="url(#logo-gradient-b)" xlink:href="#logo-gradient-a"/>
                                        <path fill="url(#logo-gradient-c)" mask="url(#logo-gradient-d)" d="M-16-16h32v32h-32z"/>
                                    </g>
                                </svg>
                            </a>
                        </h1>
                    </div>
                    NSEC - Neural Spelling Error Correction
                </div>
            </div>
        </header>

        <main>
            <section class="hero text-center">
                @yield('featured')

                <div id="content-container" class="container">
                    @yield('content')
                </div>
            </section>
        </main>

        <footer class="site-footer text-light">
            <div class="container">
                <div class="site-footer-inner">
                    <div class="brand footer-brand">
                        <ul class="footer-links list-reset">
                            <li><a href="{{ route('nsec.index') }}">NSEC</a></li>
                            <li><a href="{{ route('nsec.spell_bench') }}">NSEC-Check</a></li>
                            <li><a href="{{ route('nsec.benchmarks') }}">Benchmark</a></li>
                        </ul>
                        <div class="footer-copyright">&copy; 2019-2020 Markus Näther</div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="https://cdn.quilljs.com/1.0.0/quill.js"></script>
    @yield('extra_js')
</body>
</html>
