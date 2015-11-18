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

         <!-- PLANS TABLE -->

         <div class="col-xs-12 col-md-5">
            <div class="panel panel-primary">
                <div class="panel-heading">Plans</div>
                <div class="panel-body">

                    @if (count($testerPlans) > 0)

                        <div class="table-responsive">
                            <table class="table dashboard">
                                <thead>
                                <tr>
                                    <th>Created</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>View</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($testerPlans as $plan)

                                <tr>
                                    <td>{!! Utils::dateConverter($plan->created_at) !!}</td>
                                    <td>{!! $plan->description !!}</td>
                                    <td>

                                        @if($plan->status == 'completed')
                                            <?php $label = 'label-default' ?>
                                        @elseif($plan->status == 'pending')
                                            <?php $label = 'label-warning' ?>
                                        @else
                                            <?php $label = 'label-success' ?>
                                        @endif

                                        <span class="label {!! $label !!}">{!! $plan->status !!}</span>
                                    </td>
                                    <td><a href="#" class="viewer" data-url="{!! URL::route('dashboard.plan.view', $plan->id . '/') !!}"><span class="glyphicon glyphicon-search"></span></a></td>
                                </tr>

                                @endforeach

                                </tbody>
                            </table>
                        </div>

                    @endif

                </div>
            </div>
        </div>

         <!-- TICKETS TABLE -->

        <!--<div class="col-xs-12 col-md-5">
            <div class="panel panel-primary">
                <div class="panel-heading">Tickets</div>
                <div class="panel-body>
                    <div class="table-responsive">
                        <table class="table dashboard">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>-->

    </div>
</div>

@stop