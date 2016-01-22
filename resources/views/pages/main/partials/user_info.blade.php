{{--
|--------------------------------------------------------------------------
| User info partial for registration and viewer
|--------------------------------------------------------------------------
--}}

    <div class="form-group">
        <div class="col-xs-12 {!! $activeColumn !!}">
            {!! Form::label('active_label', 'Active') !!}
            {!! Form::select('active', [1 => 'Yes', 0 => 'No'], isset($user['active']) ? $user['active'] : null, ['class' => 'form-control input-sm', 'id' => 'active']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12 {!! $roleColumn !!}">
            {!! Form::label('role_label', 'Role') !!}
            {!! Form::select('role', $rolesOptions, $rolesSelectedOptions, ['class' => 'form-control input-sm', 'id' => 'role', 'multiple']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12 {!! $column !!}">
            {!! Form::label('first_name_label', 'First Name') !!}
            {!! Form::text('first_name', isset($user['first_name']) ? $user['first_name'] : null, ['class' => 'form-control input-sm', 'id' => 'first-name']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12 {!! $column !!}">
            {!! Form::label('last_name_label', 'Last Name') !!}
            {!! Form::text('last_name', isset($user['last_name']) ? $user['last_name'] : null, ['class' => 'form-control input-sm', 'id' => 'last-name']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12 {!! $column !!}">
            {!! Form::label('email_label', 'E-Mail Address') !!}
            {!! Form::email('email', isset($user['email']) ? $user['email'] : null, ['class' => 'form-control input-sm', 'id' => 'email']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-12 {!! $column !!}">
            {!! Form::label('password_label', 'Password') !!}
            {!! Form::password('password', ['class' => 'form-control input-sm', 'id' => 'password']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12 {!! $column !!}">
            {!! Form::label('password_confirmation_label', 'Confirm Password') !!}
            {!! Form::password('password_confirmation', ['class' => 'form-control input-sm', 'id' => 'password-confirmation']) !!}
        </div>
    </div>