{{--
|--------------------------------------------------------------------------
| Site errors
|--------------------------------------------------------------------------
|
| This template is used when displaying errors throughout the site.
|
--}}

@if ($errors->any())
    <div class="alert row">
        <ul class="col-md-12">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (Session::has('flash_message'))
    <div class="alert row">
        <ul class="col-md-12">
            <li>{!! Session::get('flash_message') !!}</li>
        </ul>
    </div>
@endif