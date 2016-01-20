{{--
|--------------------------------------------------------------------------
| Response tab header partial
|--------------------------------------------------------------------------
|
| This template is used when rendering users for tab selection.
|
--}}

    <li class="{!! $testerId == $selectedUserId ? 'active' : '' !!}">
            <a href="#{!! $testerFirstName !!}" data-toggle="tab">{!! $testerFirstName !!}</a></li>
    </li>