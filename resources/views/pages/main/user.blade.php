{{--
|--------------------------------------------------------------------------
| Admin registration
|--------------------------------------------------------------------------
|
| This template is used when showing admin registration form.
|
--}}

    @if($mode == 'edit')
        {!! Form::open(['route' => 'user.update', 'class' => 'form-horizontal viewer-form', 'id' => 'user-update-form']) !!}
        {!! Form::hidden('user_id', $user['id']) !!}
    @else
        {!! Form::open(['route' => 'auth.post.register', 'class' => 'form-horizontal viewer-form', 'id' => 'user-register-form']) !!}
    @endif

    <div class="panel panel-default" id="user-main">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-10 col-md-10">
                    <i class="fa fa-user fa-3x header-icon"></i>
                    <h4>{!! $mode == 'edit' ? $user['first_name'] : 'Add new user' !!}</h4>
                </div>
                <div class="col-xs-2 col-md-2">
                    <button type="button" class="close close-viewer" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            </div>
        </div>
        <div class="panel-body">

            @include('pages/main/partials/user_info', [
                'activeColumn'         => 'col-md-4',
                'roleColumn'           => 'col-md-6',
                'column'               => 'col-md-8',
                'user'                 => $user,
                'rolesOptions'         => $rolesOptions,
                'rolesSelectedOptions' => $rolesSelectedOptions
            ])

            @include('pages/main/partials/button', [
                'btnText'   => $mode == 'edit' ? 'Update' : 'Register',
                'direction' => 'pull-left',
                'class'     => 'btn btn-custom btn-sm',
                'id'        => $mode == 'edit' ? 'update-btn' : 'register-btn'
            ])

        </div>
    </div>

    {!! Form::close() !!}

    <script type="text/javascript">

        $(document).ready(function() {
            // User functionalities
            $('#user-main').on('click', '#update-btn, #register-btn', function() {
                var url = $('.viewer-form').attr('action');

                registerEditUserJs('{!! $mode !!}', url);
            });
        });

    </script>