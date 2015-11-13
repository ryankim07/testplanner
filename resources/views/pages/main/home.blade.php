{{--
|--------------------------------------------------------------------------
| Main page
|--------------------------------------------------------------------------
|
| This partial is used when showing main page.
|
--}}

@extends('layout.main.master')
@section('body-class','home')
@section('content')
			<div class="header text-center">
  			<h3>Test Planner</h3>
  		</div>
  		<div class="content">
  			<h2>To enroll, you will need the following:</h2>
  			{!! Html::linkRoute('plan.response', 'Start', array(2)) !!}
  		</div>
@stop