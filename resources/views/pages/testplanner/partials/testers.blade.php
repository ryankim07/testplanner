{{--
|--------------------------------------------------------------------------
| Testers form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the testers form.
|
--}}

    <div class="table-responsive nested-block">
        <legend>Browsers</legend>
        <table class="table table-striped table-hover table-bordered view-all-admin">
            <thead>
            <tr>
                <th>Name</th>
                <th class="text-center">{!! Html::image('images/chrome.png', 'Chrome', ['width' => 64, 'height' => 64]) !!}<br/>Chrome</th>
                <th class="text-center">{!! Html::image('images/firefox.png', 'Firefox', ['width' => 64, 'height' => 64]) !!}<br/>Firefox</th>
                <th class="text-center">{!! Html::image('images/ie.png', 'IE', ['width' => 64, 'height' => 64]) !!}<br/>IE</th>
                <th class="text-center">{!! Html::image('images/safari.png', 'Safari', ['width' => 64, 'height' => 64]) !!}<br/>Safari</th>
                <th class="text-center">{!! Html::image('images/apple.png', 'Apple', ['width' => 64, 'height' => 64]) !!}<br/>IOS</th>
                <th class="text-center">{!! Html::image('images/android.png', 'Android', ['width' => 64, 'height' => 64]) !!}<br/>Android</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{!! $user['first_name'] !!}</td>
                    <td class="text-center">{!! Form::radio('browser_tester[' . $user["id"] . ']', $user["id"] . ',' . $user["first_name"] . ',chrome',  null, ['class' => 'browser_tester', 'id' => 'browser_' . $user["id"] . '_chrome']) !!}</td>
                    <td class="text-center">{!! Form::radio('browser_tester[' . $user["id"] . ']', $user["id"] . ',' . $user["first_name"] . ',firefox', null, ['class' => 'browser_tester', 'id' => 'browser_' . $user["id"] . '_firefox']) !!}</td>
                    <td class="text-center">{!! Form::radio('browser_tester[' . $user["id"] . ']', $user["id"] . ',' . $user["first_name"] . ',ie',      null, ['class' => 'browser_tester', 'id' => 'browser_' . $user["id"] . '_ie']) !!}</td>
                    <td class="text-center">{!! Form::radio('browser_tester[' . $user["id"] . ']', $user["id"] . ',' . $user["first_name"] . ',safari',  null, ['class' => 'browser_tester', 'id' => 'browser_' . $user["id"] . '_safari']) !!}</td>
                    <td class="text-center">{!! Form::radio('browser_tester[' . $user["id"] . ']', $user["id"] . ',' . $user["first_name"] . ',ios',     null, ['class' => 'browser_tester', 'id' => 'browser_' . $user["id"] . '_ios']) !!}</td>
                    <td class="text-center">{!! Form::radio('browser_tester[' . $user["id"] . ']', $user["id"] . ',' . $user["first_name"] . ',android', null, ['class' => 'browser_tester', 'id' => 'browser_' . $user["id"] . '_android']) !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>