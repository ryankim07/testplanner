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
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="{!! URL::to('/dashboard') !!}">{!! Html::image('images/mophie-logo.png', 'mophie') !!}</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            @if (!Auth::guest())
                <ul class="nav navbar-nav navbar-left">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Plans <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            @if (Auth::user()->hasRole(['root', 'administrator']))
                                <li>{!! Html::linkRoute('plan.build', 'New') !!}</li>
                                <li>{!! Html::linkRoute('plan.view.all', 'View All Plans', 0) !!}</li>
                            @endif

                            <li>{!! Html::linkRoute('dashboard.view.all.assigned', 'View All Assigned Plans', []) !!}</li>
                        </ul>
                    </li>

                    @if (Auth::user()->hasRole(['root']))
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Accounts <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li>{!! Html::linkRoute('auth.register', 'Add new user') !!}</li>
                            </ul>
                        </li>
                    @endif

                </ul>

            @endif

                <ul class="nav navbar-nav navbar-right">

                @if (Auth::guest())
                    <li>{!! Html::linkRoute('auth.login', 'Login') !!}</li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{!! Auth::user()->first_name !!} <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            @if (Auth::user()->hasRole(['root', 'administrator']))
                                <li>{!! Html::linkRoute('auth.logout', 'Logout') !!}</li>
                            @elseif (Auth::user()->hasRole(['user']))
                                <li>{!! Html::linkRoute('auth.logout', 'Logout') !!}</li>
                            @endif
                        </ul>
                    </li>
                @endif

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-globe" aria-hidden="true" title="Settings"></span> <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>{!! Html::link('/', 'Main Site') !!}</li>
                        </ul>
                    </li>
                </ul>
        </div>
    </div>
</nav>