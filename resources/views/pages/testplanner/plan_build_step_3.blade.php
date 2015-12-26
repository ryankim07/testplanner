{{--
|--------------------------------------------------------------------------
| Customer registration
|--------------------------------------------------------------------------
|
| This partial is used when showing customer registraton form.
|
--}}

@extends('layout.main.master')

@section('content')

<div class="content">
	<fieldset class="form-group">
		<h5>Step 3 of 4</h5>
		<div class="row">
		    <div class="col-md-12">
		        <h6>All Fields Required</h6>
		    </div>
		</div>
          
        @include('errors.list')

        {!! Form::open(['route' => 'browser-tester.store', 'class' => '', 'id' => 'tester-build-form']) !!}

        <div class="row task-row">
            <table>
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

        @include('pages/main/partials/submit_button', ['submitBtnText' => 'Review'])

        {!! Form::close() !!}

    </fieldset>
</div>

@stop