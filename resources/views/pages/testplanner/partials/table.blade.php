{{--
|--------------------------------------------------------------------------
| All admin or all assigned table partial
|--------------------------------------------------------------------------
|
| This template is used when listing out plans.
|
--}}

    {!! Form::open(['route' => 'plan.search', 'role' => 'search']) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="clearfix">
                <div class="pull-left">
                    <h3>{!! $header !!} <span class="badge">{!! $totalPlans !!}</span></h3>
                </div>
                @if($table_type == 'view_all_admin')
                    <div class="pull-right">
                        {!! Form::select('view_user_type', ['0' => 'All', Auth::user()->id => 'My Plans'], $userId, ['class' => 'form-control input-sm', 'id' => 'view-user', 'data-url' => route('plan.view.all', ['id' => null])]) !!}
                    </div>
                @endif
            </div>
        </div>
        <div class="panel-body">
            @if($totalPlans > 0)
                <div class="row table-options">
                    <div class="pull-right">
                        {!! Form::button('Search', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered">

                        @include('pages.main.partials.table_header', $columns)

                        <tbody>
                        @foreach($plans as $plan)
                            @if($table_type == 'view_all_admin')
                                <tr class="plan-row">
                            @else
                                <tr class="toggler" data-url="{!! URL::route('plan.view', $plan->id) !!}">
                            @endif
                                <td>{!! $plan->description !!}</td>
                                <td>{!! $plan->creator !!}</td>
                                <td>{!! $plan->status !!}</td>
                                <td>{!! Utils::dateConverter($plan->created_at) !!}</td>
                                <td>{!! Utils::dateConverter($plan->updated_at) !!}</td>
                                @if($table_type == 'view_all_admin')
                                    <td>{!! Form::select('tester', $testers[$plan->id], null, ['class' => 'form-control input-sm tester']) !!}</td>
                                    <td><a href="{!! URL::route('plan.view.response', [$plan->id]) !!}" class="view-tester-plan"><span class="glyphicon glyphicon-search"></span></a></td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {!! $plans->appends($link)->render() !!}

                </div>
            @else
                @if($table_type == 'view_all_admin')
                    <p>No plans found..</p>
                @else
                    <p>You do not have any assigned plans.</p>
                @endif
            @endif
        </div>
    </div>

    {!! Form::close() !!}