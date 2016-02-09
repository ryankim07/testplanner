{{--
|--------------------------------------------------------------------------
| Response tab body partial
|--------------------------------------------------------------------------
|
| This template is used when rendering tab body for showing response.
|
--}}

<?php
    $responses = isset($plan['responses'][$browserName]) ? $plan['responses'][$browserName] : '';
    $respId    = isset($responses['ticket_resp_id']) ? $responses['ticket_resp_id'] : '';
    $status    = isset($responses['response_status']) ? $responses['response_status'] : '';
?>

    <div id="{!! $paneSelectorId !!}" class="tab-pane">
        {!! Form::hidden($browserName . '_ticket_resp_id', $respId, ['class' => 'ticket-resp-id']) !!}
        {!! Form::hidden($browserName . '_ticket_status', $status, ['class' => 'ticket-status']) !!}

        @include('pages/testplanner/partials/response_respond/plan_details', [
            'plan'           => $plan,
            'responseStatus' => $status
        ])

        @if($mode == 'responses' && empty($respId))
            @include('errors.panel_body', ['msg' => config('testplanner.messages.tickets.users_non_responses')])
        @else
            @include('pages/testplanner/partials/response_respond/plan_tickets', [
                'mode'      => $mode,
                'responses' => $responses,
                'browser'   => $browserName
            ])
        @endif
    </div>
