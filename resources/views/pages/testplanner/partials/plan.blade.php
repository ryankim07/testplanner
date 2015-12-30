{{--
|--------------------------------------------------------------------------
| Plan form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the plan form.
|
--}}

<div class="row nested-block">
    <div class="col-xs-12 col-md-8">
        <legend>Plan</legend>

        {!! Form::label('description', 'Name') !!}
        {!! Form::text('description', $description, ['class' => 'form-control input-md required', 'id' => 'description']) !!}
    </div>
</div>