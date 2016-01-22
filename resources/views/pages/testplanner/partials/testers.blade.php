{{--
|--------------------------------------------------------------------------
| Testers form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the testers input fields.
|
--}}

    <div class="page-header"><h4>Browsers</h4></div>
    <div class="nested-block">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered view-all-admin">
                <thead>
                <tr>
                    <th>Name</th>
                    <th class="text-center">{!! Html::image('images/chrome.png', 'Chrome', ['width' => 64, 'height' => 64]) !!}<br/>Chrome</th>
                    <th class="text-center">{!! Html::image('images/firefox.png', 'Firefox', ['width' => 64, 'height' => 64]) !!}<br/>Firefox</th>
                    <th class="text-center">{!! Html::image('images/ie.png', 'IE', ['width' => 64, 'height' => 64]) !!}<br/>IE</th>
                    <th class="text-center">{!! Html::image('images/safari.png', 'Safari', ['width' => 64, 'height' => 64]) !!}<br/>Safari</th>
                    <th class="text-center">{!! Html::image('images/ios.png', 'IOS', ['width' => 64, 'height' => 64]) !!}<br/>IOS</th>
                    <th class="text-center">{!! Html::image('images/android.png', 'Android', ['width' => 64, 'height' => 64]) !!}<br/>Android</th>
                </tr>
                </thead>
                <tbody>
                @foreach($testers as $tester)
                    <tr>
                        <td>{!! $tester['first_name'] !!}</td>
                        <td class="text-center">{!! Form::checkbox('tester[' . $tester["id"] . ']', $tester["id"] . ',' . $tester["first_name"] . ',chrome',  null, ['class' => 'browser-tester', 'id' => 'tester-' . $tester["id"] . '-chrome']) !!}</td>
                        <td class="text-center">{!! Form::checkbox('tester[' . $tester["id"] . ']', $tester["id"] . ',' . $tester["first_name"] . ',firefox', null, ['class' => 'browser-tester', 'id' => 'tester-' . $tester["id"] . '-firefox']) !!}</td>
                        <td class="text-center">{!! Form::checkbox('tester[' . $tester["id"] . ']', $tester["id"] . ',' . $tester["first_name"] . ',ie',      null, ['class' => 'browser-tester', 'id' => 'tester-' . $tester["id"] . '-ie']) !!}</td>
                        <td class="text-center">{!! Form::checkbox('tester[' . $tester["id"] . ']', $tester["id"] . ',' . $tester["first_name"] . ',safari',  null, ['class' => 'browser-tester', 'id' => 'tester-' . $tester["id"] . '-safari']) !!}</td>
                        <td class="text-center">{!! Form::checkbox('tester[' . $tester["id"] . ']', $tester["id"] . ',' . $tester["first_name"] . ',ios',     null, ['class' => 'browser-tester', 'id' => 'tester-' . $tester["id"] . '-ios']) !!}</td>
                        <td class="text-center">{!! Form::checkbox('tester[' . $tester["id"] . ']', $tester["id"] . ',' . $tester["first_name"] . ',android', null, ['class' => 'browser-tester', 'id' => 'tester-' . $tester["id"] . '-android']) !!}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>