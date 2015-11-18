{{--
|--------------------------------------------------------------------------
| Admin dashboard
|--------------------------------------------------------------------------
|
| This template is used when showing dashboard page.
|
--}}

@extends('layout.admin.master')

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

                    @if (count($adminPlans) > 0)

                        <div class="table-responsive">
                            <table class="table dashboard">
                                <thead>
                                <tr>
                                    <th>Created</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Testers</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($adminPlans as $plan)

                                <tr>
                                    <td>{!! Utils::dateConverter($plan['created_at']) !!}</td>
                                    <td>{!! $plan['description'] !!}</td>
                                    <td><span class="label label-default">{!! $plan['status'] !!}</span></td>
                                    <td>
                                        @if ($plan['status'] != 'completed')
                                            <select name="testers" class="viewer" data-url="{!! URL::route('admin.dashboard.plan.view', $plan['plan_id']) !!}">
                                                <option value="" selected>Select One</option>

                                                @foreach($plan['testers'] as $id => $firstName)
                                                    <option value="{!! $id !!}">{!! $firstName !!}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </td>
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