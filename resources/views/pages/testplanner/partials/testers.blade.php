{{--
|--------------------------------------------------------------------------
| Testers form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the testers input fields.
|
--}}

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered view-all-admin">
            <thead>
            <tr>
                <th>Name</th>
                <th class="text-center">{!! Html::image('images/chrome.png', 'Chrome') !!}<br/>Chrome</th>
                <th class="text-center">{!! Html::image('images/firefox.png', 'Firefox') !!}<br/>Firefox</th>
                <th class="text-center">{!! Html::image('images/ie.png', 'IE') !!}<br/>IE</th>
                <th class="text-center">{!! Html::image('images/safari.png', 'Safari') !!}<br/>Safari</th>
                <th class="text-center">{!! Html::image('images/ios.png', 'IOS') !!}<br/>IOS</th>
                <th class="text-center">{!! Html::image('images/android.png', 'Android') !!}<br/>Android</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr class="testers" data-id="{!! $user['id'] !!}" data-fname="{!! $user['first_name'] !!}" data-email="{!! $user['email'] !!}">
                    <td>{!! $user['first_name'] !!}</td>
                    <td class="text-center">{!! Form::checkbox('tester[]', 'chrome',  null, ['class' => 'browser-tester', 'id' => 'tester-' . $user['id'] . '-chrome']) !!}</td>
                    <td class="text-center">{!! Form::checkbox('tester[]', 'firefox', null, ['class' => 'browser-tester', 'id' => 'tester-' . $user['id'] . '-firefox']) !!}</td>
                    <td class="text-center">{!! Form::checkbox('tester[]', 'ie',      null, ['class' => 'browser-tester', 'id' => 'tester-' . $user['id'] . '-ie']) !!}</td>
                    <td class="text-center">{!! Form::checkbox('tester[]', 'safari',  null, ['class' => 'browser-tester', 'id' => 'tester-' . $user['id'] . '-safari']) !!}</td>
                    <td class="text-center">{!! Form::checkbox('tester[]', 'ios',     null, ['class' => 'browser-tester', 'id' => 'tester-' . $user['id'] . '-ios']) !!}</td>
                    <td class="text-center">{!! Form::checkbox('tester[]', 'android', null, ['class' => 'browser-tester', 'id' => 'tester-' . $user['id'] . '-android']) !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>