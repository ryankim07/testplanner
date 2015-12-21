{{--
|--------------------------------------------------------------------------
| Dashboard
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

    <div class="col-xs-6 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Activity Stream</div>
            <div class="panel-body">
            </div>
        </div>
    </div>

    <div class="col-xs-6 col-md-6">
        @if (count($plans) > 0)

            @foreach($plans as $type => $plan)
                <div class="row">
                    <!-- ADMIN PLANS TABLE -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            @if($type == 'admin_created_plans')
                                Assigned to others
                            @else
                                Assigned to me
                            @endif
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table dashboard">
                                    <thead>
                                    <tr>
                                        <th>Created</th>
                                        <th>Description</th>
                                        <th>Status</th>

                                        @if($type == 'admin_created_plans')
                                            <th>Testers</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($plan as $detail)

                                        <tr>
                                            <td>{!! Utils::dateConverter($detail['created_at']) !!}</td>
                                            <td>
                                                @if($type == 'admin_created_plans')
                                                    {!! $detail['description'] !!}
                                                @else
                                                    {!! Html::linkRoute('dashboard.plan.respond', $detail['description'], [$detail['id'], $detail['tester_id']]) !!}
                                                @endif
                                            </td>
                                            <td>
                                                @if($type == 'admin_created_plans')
                                                    <?php
                                                        if($detail['status'] == 'completed') {
                                                            $label = 'label-default';
                                                        } else if($detail['status'] == 'pending') {
                                                            $label = 'label-warning';
                                                        } else {
                                                            $label = 'label-success';
                                                        }
                                                    ?>

                                                    <span class="label {!! $label !!}">{!! $detail['status'] !!}</span>
                                                @else
                                                    <?php
                                                        if($detail['ticket_response_status'] == 'completed') {
                                                            $trLabel = 'label-default';
                                                        } else if($detail['ticket_response_status'] == 'pending') {
                                                            $trLabel = 'label-warning';
                                                        } else {
                                                            $trLabel = 'label-success';
                                                        }
                                                    ?>

                                                    <span class="label {!! $trLabel !!}">{!! $detail['ticket_response_status'] !!}</span>
                                                @endif
                                            </td>

                                            @if($type == 'admin_created_plans')
                                                <td>
                                                    @if ($detail['status'] != 'completed')
                                                        <select name="testers" class="viewer" data-url="{!! URL::route('dashboard.plan.view', $detail['id']) !!}">
                                                            <option value="" selected>Select One</option>

                                                            @foreach($detail['testers'] as $id => $firstName)
                                                                <option value="{!! $id !!}">{!! $firstName !!}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>

                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach

        @endif
    </div>

</div>

@stop