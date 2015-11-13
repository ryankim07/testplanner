{{--
|--------------------------------------------------------------------------
| Main header
|--------------------------------------------------------------------------
|
| This template is used when structuring main header layout.
|
--}}

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="{!! URL::to('/dashboard') !!}">{!! Html::image('images/mophie-logo.png', 'mophie') !!}</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            @if (!Auth::guest())
                <ul class="nav navbar-nav navbar-left">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Plans <span class="caret"></span></a>

                        @if (Auth::user()->hasRole(['root', 'administrator']))

                            <ul class="dropdown-menu" role="menu">
                                <li>{!! Html::link('plan/build', 'Create new plan') !!}</li>
                                <li>{!! Html::link('plan/viewAll', 'View all plans') !!}</li>
                            </ul>

                        @endif

                    </li>

                    @if (Auth::user()->hasRole(['root', 'administrator']))


                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Accounts <span class="caret"></span></a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>{!! Html::link('auth/register', 'Add new user') !!}</li>
                                </ul>

                            </li>
                    @endif

                </ul>

            @endif
            <ul class="nav navbar-nav navbar-right">

                @if (Auth::guest())
                    <li>{!! Html::link('auth/login', 'Login') !!}</li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{!! Auth::user()->first_name !!} <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>{!! Html::link('auth/logout', 'Logout') !!}</li>
                        </ul>
                    </li>
                @endif

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-globe" aria-hidden="true" title="Settings"></span> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li>{!! Html::link('/', 'Main Site') !!}</li>
                        <li>{!! Html::link('/dashboard', 'Main Dashboard') !!}</li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>