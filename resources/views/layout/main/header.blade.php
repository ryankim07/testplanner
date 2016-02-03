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
                <a href="{!! URL::to('/') !!}">{!! Html::image('images/mophie-logo.png', 'mophie') !!}</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                @if(!Auth::guest())
                    <ul class="nav navbar-nav navbar-left">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Plans <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                @if(Tools::checkUserRole(Session::get('tp.user.roles'), ['root', 'administrator']))
                                    <li>
                                        <a href="{!! URL::route('plan.build') !!}" class="menu-link"><i class="fa fa-cubes menu-link-icon"></i>Build</a>
                                    </li>
                                    <li>
                                        <a href="{!! URL::route('plan.view.all.created') !!}" class="menu-link"><i class="fa fa-cogs menu-link-icon"></i>Plans</a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{!! URL::route('plan.view.all.assigned') !!}" class="menu-link"><i class="fa fa-commenting-o menu-link-icon"></i>Respond</a>
                                </li>
                            </ul>
                        </li>
                        @if(Tools::checkUserRole(Session::get('tp.user.roles'), ['root', 'administrator']))
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Testers <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{!! URL::route('plan.view.all.responses') !!}" class="menu-link"><i class="fa fa-check-square-o menu-link-icon"></i>Responses</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Logs <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{!! URL::route('activity.view.all') !!}" class="menu-link"><i class="fa fa-tasks menu-link-icon"></i>View</a>
                                </li>
                            </ul>
                        </li>
                        @if(Tools::checkUserRole(Session::get('tp.user.roles'), ['root']))
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Accounts <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{!! URL::route('user.view.all') !!}" class="menu-link"><i class="fa fa-users menu-link-icon"></i>Users</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        @if(Tools::checkUserRole(Session::get('tp.user.roles'), ['root']))
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">System <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{!! URL::route('system.view.all') !!}" class="menu-link"><i class="fa fa-cogs menu-link-icon"></i>Settings</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                @endif
                    <ul class="nav navbar-nav navbar-right">
                    @if(Auth::guest())
                        <li>{!! Html::linkRoute('auth.login', 'Login') !!}</li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{!! Auth::user()->first_name !!} <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{!! URL::route('auth.logout') !!}" class="menu-link"><i class="fa fa-power-off menu-link-icon"></i>Logout</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    </ul>
            </div>
        </div>
    </nav>