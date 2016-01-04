{{--
|--------------------------------------------------------------------------
| Tickets form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the tickets form.
|
--}}

    @foreach($ticketsData as $ticket)
        <?php
            $id = !isset($ticket['id']) ? '' : $ticket['id'];
            $descName     = $mode == 'edit' ? "description['" . $id . "']" : 'description[]';
            $descVal      = $mode == 'edit' ? $ticket['description'] : null;
            $objName      = $mode == 'edit' ? "objective['" . $id . "']" : 'objective[]';
            $objVal       = $mode == 'edit' ? $ticket['objective'] : null;
            $testStepName = $mode == 'edit' ? "test_steps['" . $id . "']" : 'test_steps[]';
            $testStepVal  = $mode == 'edit' ? $ticket['test_steps'] : null;
        ?>

        <div class="row ticket-row nested-block" id="{!! $id !!}">
            <div class="wrapper">
                <legend>Ticket</legend>
                <a href="#" class="trash"><span class="glyphicon glyphicon-trash"></span></a>
            </div>
            <div class="form-group">
                <div class="col-xs-12 col-md-8">
                    {!! Form::label('description',  'Description') !!}
                    <div class="input-group">
                        {!! Form::text($descName, $descVal, ['class' => 'form-control input-sm ticket-description required']) !!}
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
                    {!! Form::text($objName, $objVal, ['class' => 'form-control input-sm objective required']) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12 col-md-8">
                    {!! Form::label('test_steps', 'Steps for test') !!}
                    {!! Form::textarea($testStepName, $testStepVal, ['class' => 'form-control test-steps required', 'rows' => '10']) !!}
                </div>
            </div>
        </div>
    @endforeach

    @include('pages/main/partials/button', [
         'btnText'   => 'Add another ticket',
        'direction' => 'pull-left',
        'class'     => 'btn-primary',
        'id'        => 'add-ticket-btn'
    ])