{{--
|--------------------------------------------------------------------------
| User info partial for registration and viewer
|--------------------------------------------------------------------------
--}}

    <div class="form-group">
        <div class="col-xs-12 {!! $column !!}">
            {!! Form::label('active_label', 'Active') !!}
            {!! Form::select('active', [1 => 'Yes', 0 => 'No'], $user ? $user->active : null, ['class' => 'form-control input-sm', 'id' => 'active']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12 {!! $column !!}">
            {!! Form::label('current_roles_label', 'Role') !!}
            {!! Form::select('current_roles', $rolesOptions, $rolesSelectedOptions, ['class' => 'form-control input-sm', 'id' => 'current_roles', 'multiple']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12 {!! $column !!}">
            {!! Form::label('first_name_label', 'First Name') !!}
            {!! Form::text('first_name', $user ? $user->first_name : null, ['class' => 'form-control input-sm', 'id' => 'first-name']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12 {!! $column !!}">
            {!! Form::label('last_name_label', 'Last Name') !!}
            {!! Form::text('last_name', $user ? $user->last_name : null, ['class' => 'form-control input-sm', 'id' => 'last-name']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12 {!! $column !!}">
            {!! Form::label('email_label', 'E-Mail Address') !!}
            {!! Form::email('email', $user ? $user->email : null, ['class' => 'form-control input-sm', 'id' => 'email']) !!}
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