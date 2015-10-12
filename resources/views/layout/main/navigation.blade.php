{{--
|--------------------------------------------------------------------------
| Navigation layout
|--------------------------------------------------------------------------
|
| This template is used when structuring nagigation layout.
|
--}}

<ul class="sitenav">
    <li id="enroll">
        <a href="{!! URL::to('/registration') !!}">
            <div class="icon"></div>
            <span>Enroll</span>
        </a>
    </li>
    <li id="info">
        <a href="{!! URL::to('main/info') !!}">
            <div class="icon"></div>
            <span>General Info</span>
        </a>
    </li>
    <li id="claim">
        <span data-toggle="modal" data-target="#watertestConfirm">
            <div class="icon"></div>
            <span>Submit a Claim</span>
        </span>
    </li>
</ul>
<div class="modal fade" id="watertestConfirm" tabindex="-1" role="dialog" aria-labelledby="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <?php /*<p>To submit a claim, please call mophie customer support at 1-888-8mophie or email <a href="mailto:cs@mophie.com">cs@mophie.com</a>.</p> */ ?>

                <p>You are responsible to water test your <strong>juice pack</strong> H2<strong>PRO</strong> prior to use. If, despite proper use of the <strong>juice pack</strong> H2<strong>PRO</strong>, water damage occurs to your iPhone, you may continue with submitting a claim. However, if your <strong>juice pack</strong> H2<strong>PRO</strong> has been damaged but not your iPhone, you must contact support.</p>

            </div>
            <div class="modal-footer">
                <a href="//mophie.com/support">Contact Support</a>
                <a href="{!! URL::to('/service') !!}" id="modal-continue">Continue</a>
            </div>
        </div>
    </div>
</div>