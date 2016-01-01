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
            {!! $error !!}
        @endforeach

    </div>

@endif

@if(Session::has('flash_message'))

    <div class="alert alert-success" role="alert">
        {!! Session::get('flash_message') !!}
    </div>

@endif