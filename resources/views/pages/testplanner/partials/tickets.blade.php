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
        <a href="#" class="remove-ticket-btn"><span class="glyphicon glyphicon-trash trash"></span></a>
    </div>
    <div class="form-group">
        <div class="col-xs-12 col-md-8">
            {!! Form::label('description',  'Description') !!}
            {!! Form::text('description', $ticket['description'], ['class' => 'required form-control description']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12 col-md-8">
            {!! Form::label('objective', 'Objective') !!}
            {!! Form::text('objective', $ticket['objective'], ['class' => 'required form-control objective']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12 col-md-6">
            {!! Form::label('test_steps', 'Steps for test') !!}
            {!! Form::textarea('test_steps', $ticket['test_steps'], ['class' => 'form-control test_steps', 'rows' => '10']) !!}
        </div>
    </div>

    @if ($mode == 'edit')
        {!! Form::hidden('ticket_id', $ticket['id']) !!}
    @endif
</div>

@include('pages/main/partials/button', [
    'btnText'   => 'Add another ticket',
    'direction' => 'pull-left',
    'class'     => 'btn-primary',
    'id'        => 'add-ticket-btn'
])