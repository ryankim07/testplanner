{{--
|--------------------------------------------------------------------------
| Response tab body partial
|--------------------------------------------------------------------------
|
| This template is used when rendering tab body for showing response.
|
--}}

    <div id="{!! $paneSelectorId !!}" class="tab-pane">
        <ul class="nav nav-tabs">
            {!! $tabHeaderHtml !!}
        </ul>
        <div class="tab-content inner-tab">
            {!! $tabBodyHtml !!}
        </div>
    </div>
