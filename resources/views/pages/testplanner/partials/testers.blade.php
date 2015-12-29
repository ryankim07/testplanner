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
            <th>iOS</th>
            <th>Chrome</th>
            <th>Firefox</th>
            <th>IE</th>
            <th>Safari</th>
        </tr>
        </thead>
        <tbody>

        @foreach($users as $user)

            <tr>
                <td>{!! $user['first_name'] !!}</td>
                <td>{!! Form::radio('browser[' . $user["id"] . ']', $user["id"] . ',' . $user["first_name"] . ',ios', null, ['class' => 'test_status']) !!}</td>
                <td>{!! Form::radio('browser[' . $user["id"] . ']', $user["id"] . ',' . $user["first_name"] . ',chrome', null, ['class' => 'test_status']) !!}</td>
                <td>{!! Form::radio('browser[' . $user["id"] . ']', $user["id"] . ',' . $user["first_name"] . ',firefox', null, ['class' => 'test_status']) !!}</td>
                <td>{!! Form::radio('browser[' . $user["id"] . ']', $user["id"] . ',' . $user["first_name"] . ',ie', null, ['class' => 'test_status']) !!}</td>
                <td>{!! Form::radio('browser[' . $user["id"] . ']', $user["id"] . ',' . $user["first_name"] . ',safari', null, ['class' => 'test_status']) !!}</td>
            </tr>

        @endforeach

        </tbody>
    </table>
</div>