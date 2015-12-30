{{--
|--------------------------------------------------------------------------
| Testers form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the testers form.
|
--}}

<div class="table-responsive">
    <table class="table table-striped table-hover table-bordered view-all-admin">
        <thead>
        <tr>
            <th>Name</th>
            <th>{!! Html::image('images/apple.png', 'Apple', ['width' => 64, 'height' => 64]) !!}</th>
            <th>{!! Html::image('images/chrome.png', 'Chrome', ['width' => 64, 'height' => 64]) !!}</th>
            <th>{!! Html::image('images/firefox.png', 'Firefox', ['width' => 64, 'height' => 64]) !!}</th>
            <th>{!! Html::image('images/ie.png', 'IE', ['width' => 64, 'height' => 64]) !!}</th>
            <th>{!! Html::image('images/safari.png', 'Safari', ['width' => 64, 'height' => 64]) !!}</th>
        </tr>
        </thead>
        <tbody>

        @foreach($users as $user)

            <tr>
                <td>{!! $user['first_name'] !!}</td>
                <td>{!! Form::radio('browser_tester[' . $user["id"] . ']', $user["id"] . ',' . $user["first_name"] . ',ios',     null, ['class' => 'browser_tester', 'id' => 'browser_' . $user["id"] . '_ios']) !!}</td>
                <td>{!! Form::radio('browser_tester[' . $user["id"] . ']', $user["id"] . ',' . $user["first_name"] . ',chrome',  null, ['class' => 'browser_tester', 'id' => 'browser_' . $user["id"] . '_chrome']) !!}</td>
                <td>{!! Form::radio('browser_tester[' . $user["id"] . ']', $user["id"] . ',' . $user["first_name"] . ',firefox', null, ['class' => 'browser_tester', 'id' => 'browser_' . $user["id"] . '_firefox']) !!}</td>
                <td>{!! Form::radio('browser_tester[' . $user["id"] . ']', $user["id"] . ',' . $user["first_name"] . ',ie',      null, ['class' => 'browser_tester', 'id' => 'browser_' . $user["id"] . '_ie']) !!}</td>
                <td>{!! Form::radio('browser_tester[' . $user["id"] . ']', $user["id"] . ',' . $user["first_name"] . ',safari',  null, ['class' => 'browser_tester', 'id' => 'browser_' . $user["id"] . '_safari']) !!}</td>
            </tr>

        @endforeach

        </tbody>
    </table>
</div>