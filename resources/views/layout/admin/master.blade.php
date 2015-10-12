{{--
|--------------------------------------------------------------------------
| Admin main layout
|--------------------------------------------------------------------------
|
| This template is used when structuring admin main layout.
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

        <title>LPP H2Pro Admin</title>
        <link rel="icon" href="{!! asset('favicon.ico') !!}" type="image/x-icon" />
        <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>



    </head>
    <body class="@yield('body-class')">
        @include('layout.admin.header')

        <div class="container-fluid">
            <div class="row-fluid">

                @include('layout.admin.sidebar')

                @yield('content')

                @include('layout.admin.viewer')

            </div>
        </div>

        <script type="text/javascript">
            @yield ('scripts')
        </script>
    </body>
</html>