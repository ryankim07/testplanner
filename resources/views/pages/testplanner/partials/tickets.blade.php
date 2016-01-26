{{--
|--------------------------------------------------------------------------
| Tickets form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the tickets form.
|
--}}

<?php
    $id           = !empty($ticket['id']) ? $ticket['id'] : '';
    $descName     = $mode == 'edit' ? "desc['" . $id . "']" : 'desc';
    $descVal      = $mode == 'edit' ? $ticket['desc'] : null;
    $objName      = $mode == 'edit' ? "objective['" . $id . "']" : 'objective';
    $objVal       = $mode == 'edit' ? $ticket['objective'] : null;
    $testStepName = $mode == 'edit' ? "test_steps['" . $id . "']" : 'test_steps';
    $testStepVal  = $mode == 'edit' ? $ticket['test_steps'] : null;
?>

    <div class="row ticket-row nested-block" id="{!! $id !!}">
        <div class="wrapper">
            <a href="#" class="trash"><i class="fa fa-trash-o fa-lg"></i></a>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-md-8">
                {!! Form::label('description_label',  'Description') !!}
                <div class="input-group">
                    <input type="text" name="{!! $descName !!}" value="{!! $descVal !!}" class="form-control input-sm ticket-description required">
                    <span class="input-group-addon"><i class="fa fa-eraser clear-btn"></i></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-md-8">
                {!! Form::label('objective_label', 'Objective') !!}
                <input type="text" name="{!! $objName !!}" value="{!! $objVal !!}" class="form-control input-sm objective required">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-md-8">
                {!! Form::label('test_steps_label', 'Steps for test') !!}
                <textarea name="{!! $testStepName !!}" class="form-control test-steps required" rows="10">{!! $testStepVal !!}</textarea>
            </div>
        </div>
    </div>

    @include('pages/main/partials/button', [
        'type'      => 'button',
        'btnText'   => 'Add another ticket',
        'direction' => 'pull-left',
        'class'     => $addTicketBtnType . ' btn-sm',
        'id'        => 'add-ticket-btn'
    ])