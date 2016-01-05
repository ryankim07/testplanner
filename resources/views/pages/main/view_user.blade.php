{{--
|--------------------------------------------------------------------------
| Admin registration
|--------------------------------------------------------------------------
|
| This template is used when showing admin registration form.
|
--}}

    <div class="col-xs-12 col-md-12" id="view-user-main">

        {!! Form::model($user, ['method' => 'PATCH', 'route' => ['user.update', $user->id], 'class' => 'user-form-update']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left">
                        <h3>View User</h3>
                    </div>
                    <div class="pull-right">
                        <button type="button" class="close close-viewer" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                <div class="form-group">
                    <div class="col-xs-12 col-md-8">
                        {!! Form::label('active_label', 'Active') !!}
                        {!! Form::select('active', [1 => 'Yes', 0 => 'No'], $user->active, ['class' => 'form-control input-sm', 'id' => 'active']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-8">
                        {!! Form::label('assign_role_label', 'Role') !!}
                        {!! Form::select('assign_role', $rolesOptions, $rolesSelectedOptions, ['class' => 'form-control input-sm', 'id' => 'role', 'multiple' => 'multiple']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-8">
                        {!! Form::label('first_name_label', 'First Name') !!}
                        {!! Form::text('first_name', old('name'), ['class' => 'form-control input-sm', 'id' => 'first-name']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-8">
                        {!! Form::label('last_name_label', 'Last Name') !!}
                        {!! Form::text('last_name', old('name'), ['class' => 'form-control input-sm', 'id' => 'last-name']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-8">
                        {!! Form::label('email_label', 'E-Mail Address') !!}
                        {!! Form::email('email', old('email'), ['class' => 'form-control input-sm', 'id' => 'email']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12 col-md-8">
                        {!! Form::label('password_label', 'Password') !!}
                        {!! Form::password('password', ['class' => 'form-control input-sm', 'id' => 'password']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-8">
                        {!! Form::label('password_confirmation_label', 'Confirm Password') !!}
                        {!! Form::password('password_confirmation', ['class' => 'form-control input-sm', 'id' => 'password-confirmation']) !!}
                    </div>
                </div>

                @include('pages/main/partials/submit_button', [
                    'submitBtnText' => 'Update',
                    'direction'     => 'pull-left',
                    'class'		    => 'btn-success',
                    'id'			=> 'update-btn'
                ])

            </div>
        </div>

        {!! Form::close() !!}

    </div>