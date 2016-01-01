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
                {!! Form::text('description', null, ['class' => 'form-control input-sm required', 'id' => 'plan-description']) !!}
                <span class="input-group-btn">

                    @include('pages/main/partials/button', [
                        'btnText'   => 'Clear',
                        'direction' => 'pull-left',
                        'class'     => 'btn-default btn-sm clear-btn'
                    ])

                </span>
            </div>
        </div>
    </div>