{{--
|--------------------------------------------------------------------------
| Response tab header partial
|--------------------------------------------------------------------------
|
| This template is used when rendering users for tab selection.
|
--}}

    <li class="{!! $testerId == $selectedUserId ? 'active' : '' !!}">
            <a data-toggle="tab" href="#{!! $testerFirstName !!}">{!! $testerFirstName !!}</a></li>
    </li>