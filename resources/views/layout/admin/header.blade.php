{{--
|--------------------------------------------------------------------------
| Admin header
|--------------------------------------------------------------------------
|
| This template is used when structuring admin header layout.
|
--}}

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="{!! URL::to('admin/dashboard') !!}">{!! Html::image('images/mophie-logo.png', 'mophie') !!}</a>
        </div>

    </div>
</nav>