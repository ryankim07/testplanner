{{--
|--------------------------------------------------------------------------
| Response tab body partial
|--------------------------------------------------------------------------
|
| This template is used when rendering tab body for showing response.
|
--}}

    <div id="{!! $paneSelectorId !!}" class="tab-pane">
        {!! Form::hidden($browserName . '_ticket_resp_id', $plan['responses'][$browserName]['ticket_resp_id'], ['class' => 'ticket-resp-id']) !!}
        {!! Form::hidden($browserName . '_ticket_status', $plan['responses'][$browserName]['response_status'], ['class' => 'ticket-status']) !!}

        @include('pages/testplanner/partials/response_respond/plan_details', [
            'plan'           => $plan,
            'responseStatus' => $plan['responses'][$browserName]['response_status'],
            'browser'        => $browserName
        ])

        @if($mode == 'response' && !isset($plan['responses'][$browserName]))
            @include('errors.panel_body', ['msg' => config('testplanner.messages.plan.users_non_responses')])
        @else
            @include('pages/testplanner/partials/response_respond/plan_tickets', [
                'mode'      => $mode,
                'responses' => $plan['responses'][$browserName],
                'browser'   => $browserName
            ])
        @endif
    </div>
