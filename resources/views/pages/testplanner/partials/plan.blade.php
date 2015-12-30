{{--
|--------------------------------------------------------------------------
| Plan form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the plan form.
|
--}}

<div class="row nested-block">
    <legend>Plan</legend>
    <div class="form-group">
        <div class="col-xs-12 col-md-6">
            {!! Form::label('description', 'Description') !!}
            {!! Form::text('description', $description, ['class' => 'form-control input-md required', 'id' => 'description']) !!}
        </div>
    </div>
</div>