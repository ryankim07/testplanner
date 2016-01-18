{{--
|--------------------------------------------------------------------------
| Created list
|--------------------------------------------------------------------------
|
| This template is used when showing all plans built by admin.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="view-all-created-main">

        {!! Form::open(['route' => 'plan.search', 'class' => 'form-horizontal', 'role' => 'search']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-10 col-md-10">
                        <i class="fa fa-cubes fa-3x header-icon"></i>
                        <h4>Plans <span class="badge">{!! $totalPlans !!}</span></h4>
                    </div>
                    @if($role == "root")
                    <div class="col-xs-2 col-md-2">
                        {!! Form::select('admin', $adminsList, $userId, ['class' => 'form-control input-sm', 'id' => 'admin', 'data-url' => route('plan.view.all.created', ['id' => null])]) !!}
                    </div>
                    @endif
                </div>
            </div>
            <div class="panel-body">
                @if($totalPlans > 0)
                    <div class="row table-options">
                        <div class="pull-right">
                            {!! Form::button('Search', ['class' => 'btn btn-custom btn-sm', 'type' => 'submit']) !!}
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">

                            @include('pages.main.partials.table_header', $columns)

                            <tbody>
                            @foreach($plans as $plan)
                                <tr class="plan-row">
                                    <td>{!! $plan->description !!}</td>
                                    <td>{!! $plan->full_name !!}</td>

                                    <?php
                                        if($plan->status == 'complete') {
                                            $trLabel = 'label-default';
                                        } else if($plan->status  == 'progress') {
                                            $trLabel = 'label-warning';
                                        } else {
                                            $trLabel = 'label-success';
                                        }
                                    ?>

                                    <td class="text-center"><span class="label {!! $trLabel !!}">{!! $plan->status !!}</span></td>
                                    <td>{!! Utils::dateConverter($plan->created_at) !!}</td>
                                    <td>{!! Utils::dateConverter($plan->updated_at) !!}</td>
                                    <td class="text-center"><a href="{!! URL::route('plan.view', $plan->id) !!}"><i class="fa fa-pencil-square-o fa-lg"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {!! $plans->appends($link)->render() !!}

                    </div>
                @else
                    <p>No plans found..</p>
                @endif
            </div>
        </div>

        {!! Form::close() !!}

    </div>

@stop