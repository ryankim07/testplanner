{{--
|--------------------------------------------------------------------------
| Response tab header partial
|--------------------------------------------------------------------------
|
| This template is used when rendering browser names for tab selection.
|
--}}

    <li class="">
        <a href="#{!! $selectorId !!}" data-toggle="tab">{!! $image !!} {!! ucwords($selectorName) !!}</a>
    </li>