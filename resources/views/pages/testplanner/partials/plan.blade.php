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
    <div class="col-xs-12 col-md-8">

        {!! Form::label('description', 'Description') !!}

        <div class="input-group">

            {!! Form::text('description', $description, ['class' => 'form-control input-md required', 'id' => 'plan-description']) !!}

            <span class="input-group-btn">
                @include('pages/main/partials/button', [
                    'btnText'   => 'Clear',
                    'direction' => 'pull-left',
                    'class'     => 'btn-default',
                    'id'        => 'clear-btn'
                ])
            </span>
        </div>
    </div>
</div>