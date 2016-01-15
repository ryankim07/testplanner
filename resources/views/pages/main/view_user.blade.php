{{--
|--------------------------------------------------------------------------
| Admin registration
|--------------------------------------------------------------------------
|
| This template is used when showing admin registration form.
|
--}}

    {!! Form::open(['route' => 'user.update', 'class' => 'form-horizontal', 'id' => 'user-form-update']) !!}
    {!! Form::hidden('user_id', $user->id) !!}

    <div class="panel panel-default" id="view-user-main">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-10 col-md-10">
                    <i class="fa fa-user fa-3x header-icon"></i>
                    <h4>{!! $user->first_name !!}</h4>
                </div>
                <div class="col-xs-2 col-md-2">
                    <button type="button" class="close close-viewer" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            </div>
        </div>
        <div class="panel-body">

            <div class="alert" role="alert"></div>

            @include('pages/main/partials/user_info', [
                'activeColumn'         => 'col-md-4',
                'roleColumn'           => 'col-md-6',
                'column'               => 'col-md-8',
                'user'                 => $user,
                'rolesOptions'         => $rolesOptions,
                'rolesSelectedOptions' => $rolesSelectedOptions
            ])

            @include('pages/main/partials/button', [
                'btnText'   => 'Update',
                'direction' => 'pull-left',
                'class'     => 'btn btn-custom btn-sm',
                'id'        => 'update-btn'
            ])

        </div>
    </div>

    {!! Form::close() !!}

    <script type="text/javascript">

        $(document).ready(function() {
            // User functionalities
            loadUsersJs('{!! URL::to('user/update') !!}'');
        });

    </script>