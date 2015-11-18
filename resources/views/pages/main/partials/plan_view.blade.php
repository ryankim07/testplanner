{{--
|--------------------------------------------------------------------------
| Plan view form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the form.
|
--}}

{!! Form::open(['route' => $formAction, 'class' => '', 'id' => 'plan-view-form']) !!}

<div class="row">
    <div class="col-xs-8 col-md-8">

        @foreach($responses as $response)

        <div class="col-xs-6">
            <ul class="list-unstyled">
                <li>
                    {!! Form::label('ticket', 'Ticket:') !!}
                    {!! Form::text('ticket', $response->descrition, ['readonly' => 'readonly']) !!}
                </li>
                <li>
                    {!! Form::label('objective', 'Objective:') !!}
                    {!! Form::text('objective', $response->objective, ['readonly' => 'readonly']) !!}
                </li>
                <li>
                    {!! Form::label('test_steps', 'Steps to test:') !!}
                    {!! Form::textarea('notes', $response->test_steps, ['size' => '10x40', 'readonly' => 'readonly']) !!}
                </li>
            </ul>
        </div>
        <div class="col-xs-2">
            {!! Form::radio('status', 1) !!}
            {!! Form::radio('status', 0) !!}
        </div>
        <div class="col-xs-4">
            {!! Form::label('notes', 'Notes:') !!}
            {!! Form::textarea('notes', $response->notes, ['size' => '10x40']) !!}
        </div>

        @endforeach
    </div>
</div>

{!! Form::close() !!}