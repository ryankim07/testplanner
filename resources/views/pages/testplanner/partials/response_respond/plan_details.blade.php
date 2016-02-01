{{--
|--------------------------------------------------------------------------
| Response plan details partial
|--------------------------------------------------------------------------
|
| This template is used when rendering plan details.
|
--}}

    <div class="row nested-block">
        <legend>Plan Details</legend>
        <div class="col-xs-12 col-md-3">
            <div class="form-group">
                <p>Admin: <strong>{!! $plan['reporter'] !!}</strong></p>
                <p>Assignee: <strong>{!! $plan['assignee'] !!}</strong></p>
            </div>
        </div>
        <div class="col-xs-12 col-md-3">
            <div class="form-group">
                <p>Started: <strong>{!! Tools::dateConverter($plan['started_at']) !!}</strong></p>
                <p>Expires: <strong>{!! Tools::dateConverter($plan['expired_at']) !!}</strong></p>
            </div>
        </div>
        <div class="col-xs-12 col-md-3">
            <div class="form-group">
                <p>Created: <strong>{!! Tools::dateConverter($plan['created_at']) !!}</strong></p>
                <p>Updated: <strong>{!! Tools::dateConverter($plan['updated_at']) !!}</strong></p>
            </div>
        </div>
        <div class="col-xs-12 col-md-3">
            <div class="form-group">
                <p>Status:

                    <?php
                    if($responseStatus == 'complete') {
                        $trLabel = 'label-default';
                    } else if($responseStatus == 'progress') {
                        $trLabel = 'label-warning';
                    } else {
                        $trLabel = 'label-success';
                    }
                    ?>

                    <span class="label {!! $trLabel !!}">{!! empty($responseStatus) ? 'NEW' : strtoupper($responseStatus) !!}</span>
                </p>
            </div>
        </div>
    </div>