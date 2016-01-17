{{--
|--------------------------------------------------------------------------
| Tickets form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the tickets form.
|
--}}

<?php
    $id           = !isset($ticket['id']) ? '' : $ticket['id'];
    $descName     = $mode == 'edit' ? "desc['" . $id . "']" : 'desc[]';
    $descVal      = $mode == 'edit' ? $ticket['desc'] : null;
    $objName      = $mode == 'edit' ? "objective['" . $id . "']" : 'objective[]';
    $objVal       = $mode == 'edit' ? $ticket['objective'] : null;
    $testStepName = $mode == 'edit' ? "test_steps['" . $id . "']" : 'test_steps[]';
    $testStepVal  = $mode == 'edit' ? $ticket['test_steps'] : null;
?>

    <div class="row ticket-row nested-block" id="{!! $id !!}">
        <div class="wrapper">
            <legend>Ticket</legend>
            <a href="#" class="trash"><i class="fa fa-trash-o fa-lg"></i></a>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-md-8">
                {!! Form::label('description_label',  'Description') !!}
                <div class="input-group">
                    {!! Form::text($descName, $descVal, ['class' => 'form-control input-sm ticket-description required']) !!}
                    <span class="input-group-addon">
                        <i class="fa fa-eraser clear-btn"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-md-8">
                {!! Form::label('objective_label', 'Objective') !!}
                {!! Form::text($objName, $objVal, ['class' => 'form-control input-sm objective required']) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-md-8">
                {!! Form::label('test_steps_label', 'Steps for test') !!}
                {!! Form::textarea($testStepName, $testStepVal, ['class' => 'form-control test-steps required', 'rows' => '10']) !!}
            </div>
        </div>
    </div>

    @include('pages/main/partials/button', [
        'btnText'   => 'Add another ticket',
        'direction' => 'pull-left',
        'class'     => $addTicketBtnType . ' btn-sm',
        'id'        => 'add-ticket-btn'
    ])