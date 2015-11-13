{{--
|--------------------------------------------------------------------------
| Case form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the case form.
|
--}}

<div class="row">
    <div class="col-md-12">
        {!! Form::label('description', 'Please enter a description for this plan') !!}
        {!! Form::text('description', null, ['class' => 'required', 'id' => 'description']) !!}
        {!! Form::hidden('creator_id', $creatorId) !!}
    </div>
</div>