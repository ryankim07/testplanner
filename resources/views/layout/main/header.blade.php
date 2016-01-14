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
                                @if(Auth::user()->hasRole(['root', 'administrator']))
                                    <li>{!! Html::linkRoute('plan.build', 'Build') !!}</li>
                                    <li>{!! Html::linkRoute('plan.view.all.created', 'View/Edit', 0) !!}</li>
                                @endif
                                <li>{!! Html::linkRoute('plan.view.all.assigned', 'Respond', []) !!}</li>
                            </ul>
                        </li>
                        @if(Auth::user()->hasRole(['root']))
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Testers <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>{!! Html::linkRoute('plan.view.all.responses', 'Responses', 0) !!}</li>
                                </ul>
                            </li>
                        @endif
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Logs <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li>{!! Html::linkRoute('activity.view.all', 'View all') !!}</li>
                            </ul>
                        </li>
                        @if(Auth::user()->hasRole(['root']))
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Accounts <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>{!! Html::linkRoute('auth.register', 'Add') !!}</li>
                                    <li>{!! Html::linkRoute('user.view.all', 'View/Edit') !!}</li>
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
                                <li>{!! Html::linkRoute('auth.logout', 'Logout') !!}</li>
                            </ul>
                        </li>
                    @endif
                    </ul>
            </div>
        </div>
    </nav>