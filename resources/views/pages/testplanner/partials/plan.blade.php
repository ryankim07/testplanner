{{--
|--------------------------------------------------------------------------
| Plan form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the plan form.
|
--}}

    <div class="row nested-block">
        <legend>Plan Details</legend>
        <div class="form-group">
            <div class="col-xs-12 col-md-8">
                {!! Form::label('description_label', 'Description:') !!}
                <div class="input-group">
                    {!! Form::text('description', null, ['class' => 'form-control input-sm required', 'id' => 'plan-description']) !!}
                    <span class="input-group-btn">

                        @include('pages/main/partials/button', [
                            'btnText'   => 'Clear',
                            'direction' => 'pull-left',
                            'class'     => 'btn-primary btn-sm clear-btn'
                        ])

                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-md-2">
                {!! Form::label('start_at_label', 'Test Start Date:') !!}
                <div class="input-group date" id="started_at">
                    {!! Form::text('started_at', null, ['class' => 'form-control input-sm required']) !!}
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-md-2">
                {!! Form::label('expiration_at_label', 'Test Expiration Date:') !!}
                <div class="input-group date" id="expired_at">
                    {!! Form::text('expired_at', null, ['class' => 'form-control input-sm required']) !!}
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>