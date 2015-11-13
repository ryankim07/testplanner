{{--
|--------------------------------------------------------------------------
| Admin dashboard
|--------------------------------------------------------------------------
|
| This template is used when showing dashboard page.
|
--}}

@extends('layout.main.master')

@section('content')

<div class="col-xs-12 col-md-12" id="main">
    <div class="page-header">
        <h2>Dashboard</h2>
    </div>

    @include('errors.list')

    <div class="row">
        <div class="col-xs-12 col-md-10">
            <div class="panel panel-primary">
                <div class="panel-heading">Summary</div>
                <div class="panel-body">

                </div>
            </div>
        </div>
    </div>
</div>

@stop