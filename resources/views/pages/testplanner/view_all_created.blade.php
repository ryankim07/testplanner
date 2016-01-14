{{--
|--------------------------------------------------------------------------
| Admin assigned plan list
|--------------------------------------------------------------------------
|
| This template is used when showing all plans assigned to admin.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="view-all-created-main">

        {!! Form::open(['route' => 'plan.search', 'class' => 'form-horizontal', 'role' => 'search']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4>Plans <span class="badge">{!! $totalPlans !!}</span></h4>
                    </div>
                    @if($role == "root")
                        <div class="pull-right">
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
                                <tr class="toggler" data-url="{!! URL::route('plan.view', $plan->id) !!}">
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

    <script type="text/javascript">

        $(document).ready(function() {
            // View or edit single plan
            $('#view-all-created-main').on('click', '.toggler', function() {
                window.location.href = $(this).data('url');
            });

            $('#view-all-created-main').on('change', '#admin', function() {
                var route   = $(this).data('url');
                var adminId = $(this).val();

                window.location.href =  route + '/' + adminId;
            });
        });

    </script>

@stop