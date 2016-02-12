{{--
|--------------------------------------------------------------------------
| All users list
|--------------------------------------------------------------------------
|
| This template is used when showing all users for the system.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12" id="view-all-users-main">

        {!! Form::open(['route' => 'user.search', 'role' => 'search']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <i class="fa fa-users fa-3x header-icon"></i>
                        <h4>Users</h4>
                    </div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                @if(count($users) == 0)
                    <p>{!! config('testplanner.messages.plan.no_users_found') !!}</p>
                @else
                    <div class="row table-options">
                        <div class="pull-right">

                            @include('pages/main/partials/submit_and_button', [
                                'direction'   => 'pull-right',
                                'btnText'     => 'Add New',
                                'btnClass'    => 'btn-custom btn-sm',
                                'btnId'       => 'add-btn',
                                'btnData'     => 'register',
                                'submitText'  => 'Search',
                                'submitClass' => 'btn-custom btn-sm',
                                'submitId'    => 'search-btn'
                            ])

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">

                            @include('pages.main.partials.table_header', $columns)

                            <tbody>
                            @foreach($users as $user)
                                <tr class="users-row" data-row="{!! $user->id !!}:{!!$user->first_name !!}:{!! $user->last_name !!}:{!! $user->email !!}:{!! $user->active !!}:{!! $user->role_ids !!}">
                                    <td>{!! $user->first_name !!}</td>
                                    <td>{!! $user->last_name !!}</td>
                                    <td>{!! $user->email !!}</td>
                                    <td>{!! $user->active == 1 ? 'Yes' : 'No' !!}</td>
                                    <td>{!! ucwords($user->role_names) !!}</td>
                                    <td>{!! Tools::dateConverter($user->created_at) !!}</td>
                                    <td>{!! Tools::dateConverter($user->updated_at) !!}</td>
                                    <td class="text-center"><a href="{!! URL::route('user.view') !!}" class="edit-link" data-click="edit"><i class="fa fa-pencil-square-o fa-lg"></i></a></td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {!! Form::close() !!}

        {!! $users->appends('')->render() !!}

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            $('#view-all-users-main').on('click', '#add-btn, .edit-link', function(e) {
                var mode = $(this).data('click');
                var url  = '{!! URL::route('auth.register') !!}';
                var data = '';

                if (mode == 'edit') {
                    e.preventDefault();
                    var userRow = $(this).closest('.users-row');
                    url = $(this).attr('href');

                    data = {info : userRow.data('row')};
                }

                // Display registration or edit form
                loadUsersJs(url, data);
            });
        });

    </script>

    @include('layout.main.viewer')
@stop
