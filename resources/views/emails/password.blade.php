{{--
|--------------------------------------------------------------------------
| Admin password reset
|--------------------------------------------------------------------------
|
| This template is used when showing admin password reset email.
|
--}}

Click here to reset your password: {!! url('password/getReset/'.$token) !!}