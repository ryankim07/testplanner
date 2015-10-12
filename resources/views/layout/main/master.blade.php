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

        <title>Juice Pack H2Pro Enrollment</title>
        <link rel="icon" href="{!! asset('favicon.ico') !!}" type="image/x-icon" />

        <!-- CSS -->
        {!! Html::style('css/app.css') !!}

        <!-- Scripts -->
        {!! Html::script('js/vendor.js') !!}
        {!! Html::script('js/app.js') !!}
    </head>
    <body class="@yield('body-class')">
	  
        @include('layout.main.header')

        <div class="container">
            @if (App::isDownForMaintenance())
                @yield('content')
            @else
                @if (isset($page))
                    @if ($page == 'home')
                        @yield('content')
                        @include('layout.main.navigation')
                    @else
                        @include('layout.main.navigation')
                        @yield('content')
                    @endif
                @else
                    @include('layout.main.navigation')
                    @yield('content')
                @endif
            @endif
		</div>
    </body>
</html>