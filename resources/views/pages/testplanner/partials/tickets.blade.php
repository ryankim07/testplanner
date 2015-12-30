{{--
|--------------------------------------------------------------------------
| Tickets form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the tickets form.
|
--}}

<div class="row ticket-row nested-block">

    @if ($mode == 'edit')
        {!! Form::hidden('ticket_id', $ticket['id']) !!}
    @endif

    <div class="wrapper">
        <legend>Ticket</legend>
        <a href="#" class="remove-ticket-btn"><span class="glyphicon glyphicon-trash trash"></span></a>
    </div>
    <div class="col-md-12">
        {!! Form::label('description',  'Description') !!}
        {!! Form::text('description', $ticket['description'], ['class' => 'required form-control description']) !!}
    </div>
    <div class="col-md-12">
        {!! Form::label('objective', 'Objective') !!}
        {!! Form::text('objective', $ticket['objective'], ['class' => 'required form-control objective']) !!}
    </div>
    <div class="col-md-12">
        {!! Form::label('test_steps', 'Steps for test') !!}
    </div>
    <div class="col-md-12">
        {!! Form::textarea('test_steps', $ticket['test_steps'], ['class' => 'test_steps', 'size' => '100x10']) !!}
    </div>
</div>

@include('pages/main/partials/button', [
    'btnText'   => 'Add another ticket',
    'direction' => 'pull-left',
    'id'        => 'add-ticket-btn'
])