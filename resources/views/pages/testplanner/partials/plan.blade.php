{{--
|--------------------------------------------------------------------------
| Plan form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the plan form.
|
--}}

<div class="row">
    <div class="col-xs-12 col-md-8">

        <?php
            $label = $mode == 'create' ? 'Please enter a description for this plan' : 'Plan description';
        ?>

        {!! Form::label('description', $label) !!}
        {!! Form::text('description', $description, ['class' => 'form-control input-md required', 'id' => 'description']) !!}
    </div>
</div>