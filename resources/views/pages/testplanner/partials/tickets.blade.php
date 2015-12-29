{{--
|--------------------------------------------------------------------------
| Tickets form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the tickets form.
|
--}}

<div class="row ticket-row">

    @if ($mode == 'edit')
        {!! Form::hidden('ticket_id', $ticket['id']) !!}
    @endif

    <div class="col-md-12">

        <?php
            $label = $mode == 'create' ? 'Please enter the description for ticket' : 'Ticket description';
        ?>

        {!! Form::label('description', $label) !!}

        @if ($mode == 'create')
            {!! Form::button('Remove Ticket', ['class' => 'btn btn-primary btn-xs remove-ticket-btn']) !!}
        @endif

        {!! Form::text('description', $ticket['description'], ['class' => 'required form-control description']) !!}
    </div>
    <div class="col-md-12">

        <?php
            $label = $mode == 'create' ? 'Please enter the objective' : 'Objective';
        ?>

        {!! Form::label('objective', $label) !!}
        {!! Form::text('objective', $ticket['objective'], ['class' => 'required form-control objective']) !!}
    </div>
    <div class="col-md-12">

        <?php
            $label = $mode == 'create' ? 'Please enter the steps for test' : 'Steps for test';
        ?>

        {!! Form::label('test_steps', $label) !!}
    </div>
    <div class="col-md-12">
        {!! Form::textarea('test_steps', $ticket['test_steps'], ['class' => 'test_steps', 'size' => '100x10']) !!}

        @if ($mode == 'create')
            <div class="button-group">
                {!! Form::button('Add New Ticket', ['class' => 'btn btn-primary btn-xs add-ticket-btn']) !!}
            </div>
        @endif

    </div>
</div>