{{--
|--------------------------------------------------------------------------
| Admin registrations list
|--------------------------------------------------------------------------
|
| This template is used when showing all registrations.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12" id="main">

        {!! Form::open(['route' => 'plan.search', 'class' => 'form-horizontal', 'role' => 'search']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left"><h3>All plans assigned to others <span class="badge">{!! $totalPlans !!}</span></h3></div>
                </div>
            </div>
            <div class="panel-body">

                @if ($totalPlans > 0)

                    <div class="row table-options">
                        <div class="pull-right">
                            {!! Form::button('Search', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered view-all-admin">

                            @include('pages.main.partials.table_header', $columns)

                            <tbody>

                            @foreach($plans as $plan)

                                <tr class="plan-row">
                                    <td>{!! $plan->description !!}</td>
                                    <td>{!! $plan->creator !!}</td>
                                    <td>{!! $plan->status !!}</td>
                                    <td>{!! Utils::dateConverter($plan->created_at) !!}</td>
                                    <td>{!! Utils::dateConverter($plan->updated_at) !!}</td>
                                    <td>{!! Form::select('tester', $testers[$plan->id], null, ['class' => 'form-control input-sm tester']) !!}</td>
                                    <td><a href="{!! URL::route('plan.view.response', [$plan->id]) !!}" class="view-tester-plan"><span class="glyphicon glyphicon-search"></span></a></td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>

                        {!! $plans->appends($link)->render() !!}

                    </div>

                @else

                    <p>Records not found.</p>

                @endif

            </div>
        </div>

        {!! Form::close() !!}

    </div>

<script type="text/javascript">

$(document).ready(function() {
    // Select row for viewing
    $('.view-tester-plan').on('click', function (e) {
        e.preventDefault();

        var parent = $(this).closest('tr');
        var tester = parent.find('.tester').val();
        var url    = $(this).attr('href');

        window.location.href = url + '/' + tester;
    });
});

</script>

@stop