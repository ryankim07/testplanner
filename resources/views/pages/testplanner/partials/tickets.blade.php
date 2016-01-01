{{--
|--------------------------------------------------------------------------
| Tickets form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the tickets form.
|
--}}

    <div class="row ticket-row nested-block">
        <div class="wrapper">
            <legend>Ticket</legend>
            <a href="#" class="trash"><span class="glyphicon glyphicon-trash"></span></a>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-md-8">
                {!! Form::label('description',  'Description') !!}
                <div class="input-group">
                    {!! Form::text('description[]', null, ['class' => 'form-control input-sm ticket-description required']) !!}
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
        <div class="form-group">
            <div class="col-xs-12 col-md-8">
                {!! Form::label('objective', 'Objective') !!}
                {!! Form::text('objective[]', null, ['class' => 'form-control input-sm objective required']) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-md-8">
                {!! Form::label('test_steps', 'Steps for test') !!}
                {!! Form::textarea('test_steps[]', null, ['class' => 'form-control test_steps required', 'rows' => '10']) !!}
            </div>
        </div>
    </div>

    @include('pages/main/partials/button', [
        'btnText'   => 'Add another ticket',
        'direction' => 'pull-left',
        'class'     => 'btn-primary',
        'id'        => 'add-ticket-btn'
    ])