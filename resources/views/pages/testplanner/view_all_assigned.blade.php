{{--
|--------------------------------------------------------------------------
| Testers assigned plan list
|--------------------------------------------------------------------------
|
| This template is used when showing all assigned plans to testers.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="view-all-assigned-main">

        @include('pages.testplanner.partials.table', [
            'table_type' => 'view-all-assigned',
            'header'     => 'All plans assigned to me'
        ])

    </div>

@stop