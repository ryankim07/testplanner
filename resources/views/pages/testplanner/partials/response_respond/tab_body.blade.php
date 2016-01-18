{{--
|--------------------------------------------------------------------------
| Response tab body partial
|--------------------------------------------------------------------------
|
| This template is used when rendering tab body for showing response.
|
--}}

    <div id="{!! $testerFirstName !!}" class="tab-pane fade {!! $testerId == $selectedUserId ? 'in active' : '' !!}">
        @include('pages/testplanner/partials/response_respond/plan_details', ['plan' => $plan])
        @include('pages/testplanner/partials/response_respond/plan_tickets', [
            'mode' => $mode,
            'plan' => $plan
        ])
    </div>