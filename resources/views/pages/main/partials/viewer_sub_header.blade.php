{{--
|--------------------------------------------------------------------------
| Admin viewer sub header partial
|--------------------------------------------------------------------------
|
| This partial is used when showing or editing the viewer sub header.
|
--}}

    <div class="clearfix">
        <div class="pull-left">
            <h4>{!! $viewerSubHeaderText !!}</h4>
        </div>
        <div class="pull-right">
            <ul class="nav navbar-nav navbar-left">
                @if(isset($options) && count($options) > 0)
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-bars fa-lg" aria-hidden="true" title="Options"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            @foreach($options as $option)
                                <li>{!! Html::linkRoute($option['url'], $option['link_name'], $option['params'], $option['attributes']) !!}</li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                <li class="btn">
                    <button type="button" class="close close-viewer" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </li>
            </ul>
        </div>
    </div>