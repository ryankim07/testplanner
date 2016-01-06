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
                <div class="clearfix">
                    <div class="pull-left">
                        <h3>All users <span class="badge">{!! $totalUsers !!}</span></h3>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                @if($totalUsers == 0)
                    <p>No users found..</p>
                @else
                    <div class="row table-options">
                        <div class="pull-right">
                            {!! Form::button('Search', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">

                            @include('pages.main.partials.table_header', $columns)

                            <tbody>
                            @foreach($users as $user)
                                <tr class="toggler" data-url="{!! URL::route('user.view', $user->id) !!}">
                                    <td>{!! $user->first_name !!}</td>
                                    <td>{!! $user->last_name !!}</td>
                                    <td>{!! $user->email !!}</td>
                                    <td>{!! isset($user->active) == true ? 'Yes' : 'No' !!}</td>
                                    <td>{!! $user->role_names !!}</td>
                                    <td>{!! Utils::dateConverter($user->created_at) !!}</td>
                                    <td>{!! Utils::dateConverter($user->updated_at) !!}</td>
                                </tr>
                            @endforeach
                        </table>

                        {!! $users->appends($link)->render() !!}

                    </div>
                @endif
            </div>
        </div>

        {!! Form::close() !!}

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            $('#view-all-users-main').on('click', '.toggler', function(e) {
                e.preventDefault();

                var currentClass = $('#view-all-users-main').attr('class');

                if (currentClass != 'col-xs-12 col-md-8') {
                    // Control width of both columns
                    $('#view-all-users-main').toggleClass('col-md-12 col-md-8');
                    $('#viewer-main').toggleClass('col-md-0 col-md-4');
                }

                // Selecting rows on mobile
                if (currentClass == 'col-xs-12 col-md-12') {
                    $('#view-all-users-main').css({'z-index': '1000'});
                }

                $.when(
                    $.ajax({
                        method: "GET",
                        url: $(this).data('url'),
                        dataType: "json",
                        success: function (resp) {
                            $('#viewer-main').html(resp.viewBody);
                        }
                    })
                ).done(function (resp) {
                    // Close viewer
                    $('.close-viewer').on('click', function (e) {
                        e.preventDefault();
                        $('#view-all-users-main').toggleClass('col-md-12 col-md-8');
                        $('#viewer-main').toggleClass('col-md-0 col-md-4');
                        $('#viewer-main').empty();
                    });
                });
            });
        });

    </script>

@stop