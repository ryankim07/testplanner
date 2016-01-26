{{--
|--------------------------------------------------------------------------
| Response tab body partial
|--------------------------------------------------------------------------
|
| This template is used when rendering tab body for showing response.
|
--}}

    <div id="{!! $testerFirstName !!}" class="">
        @include('pages/testplanner/partials/response_respond/plan_details', [
            'plan'     => $plan,
            'browsers' => $browsers
        ])

        @if($mode == 'response' && $totalResponses == 0)
            @include('errors.panel_body', ['msg' => config('testplanner.messages.plan.users_non_responses')])
        @else
            @include('pages/testplanner/partials/response_respond/plan_tickets', [
                'mode' => $mode,
                'plan' => $plan
            ])
        @endif
    </div>
