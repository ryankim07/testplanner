{{--
|--------------------------------------------------------------------------
| Case form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the case form.
|
--}}

@for ($i = 1; $i <= $$numTasksToCreate; $i++)

    <div class="row">
        <div class="col-md-12">
            {!! Form::label('description', 'Please enter a description for ticket ' . $i) !!}
            {!! Form::text('description', null, ['class' => 'required', 'id' => 'description']) !!}
        </div>
    </div>

@endfor