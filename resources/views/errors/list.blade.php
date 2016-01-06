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
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>
            {!! $error !!}
        @endforeach
    </div>
@endif

@if(Session::has('flash_message'))
    <div class="alert alert-success" role="alert">
        <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
        <span class="sr-only">Success:</span>
        {!! Session::get('flash_message') !!}
    </div>
@endif