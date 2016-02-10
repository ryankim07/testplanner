{{--
|--------------------------------------------------------------------------
| Response tab body partial
|--------------------------------------------------------------------------
|
| This template is used when rendering tab body for showing response.
|
--}}

    <div id="{!! $paneSelectorId !!}" class="tab-pane">
        @include('pages/testplanner/partials/response_respond/plan_details', [
            'plan'           => $plan,
            'responseStatus' => $responses['response_status']
        ])

        @if($mode == 'responses' && (count($responses['tickets']) == 0 || $responses['response_status'] == '' || $responses['response_status'] == 'new'))
            @include('errors.panel_body', ['msg' => config('testplanner.messages.tickets.users_non_responses')])
        @else
            @include('pages/testplanner/partials/response_respond/plan_tickets', [
                'mode'      => $mode,
                'responses' => $responses,
                'browser'   => $browserName
            ])

            {!! Form::hidden($browserName . '_ticket_resp_id', $responses['ticket_resp_id'], ['class' => 'ticket-resp-id']) !!}
            {!! Form::hidden($browserName . '_ticket_status', $responses['response_status'], ['class' => 'ticket-status']) !!}
        @endif
    </div>