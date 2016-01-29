{{--
|--------------------------------------------------------------------------
| Response tab header partial
|--------------------------------------------------------------------------
|
| This template is used when rendering users for tab selection.
|
--}}

    <li class="">
        <a href="#{!! $selectorId !!}" data-toggle="tab">{!! ucwords($selectorName) !!}</a>
    </li>