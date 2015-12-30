{{--
|--------------------------------------------------------------------------
| Main layout
|--------------------------------------------------------------------------
|
| This template is used when structuring main layout.
|
--}}

        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @if (!isset($page))
        <?php
        header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.
        ?>
    @endif

    <title>Test Planner</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}" type="image/x-icon" />
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <!-- CSS -->
    {!! Html::style('css/bootstrap.min.css') !!}
    {!! Html::style('css/app.css') !!}
    {!! Html::style('css/main.css') !!}

            <!-- Scripts -->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    {!! Html::script('js/vendor.js') !!}
    {!! Html::script('js/main.js') !!}

</head>
<body class="@yield('body-class')">
@include('layout.main.header')

<div class="container-fluid">
    <div class="row-fluid">

        @yield('content')

    </div>
</div>

<script type="text/javascript">
    @yield ('scripts')
</script>
</body>
</html>