{{--
|--------------------------------------------------------------------------
| Site errors
|--------------------------------------------------------------------------
|
| This template is used when displaying errors throughout the site.
|
--}}

@if($errors->any())
    <div class="alert alert-danger" role="alert">
        @foreach($errors->all() as $error)
            <i class="fa fa-exclamation-circle fa-lg" aria-hidden="true"></i>
            <span class="sr-only">Error:</span>
            {!! $error !!}<br/>
        @endforeach
    </div>
@endif

@if(Session::has('flash_success'))
    <div class="alert alert-success" role="alert">
        <i class="fa fa-check-circle fa-lg" aria-hidden="true"></i>
        <span class="sr-only">Success:</span>
        {!! Session::get('flash_success') !!}
    </div>
@endif

@if(Session::has('flash_error'))
    <div class="alert alert-danger" role="alert">
        <i class="fa fa-exclamation-circle fa-lg" aria-hidden="true"></i>
        <span class="sr-only">Error:</span>
        {!! Session::get('flash_error') !!}
    </div>
@endif