{{--
|--------------------------------------------------------------------------
| Main sidebar layout
|--------------------------------------------------------------------------
|
| This template is used when structuring admin sidebar layout.
|
--}}

@if (!Auth::guest())

    <div class="sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{!! url('admin/customers') !!}"><i class="glyphicon glyphicon-user" aria-hidden="true" title="Customers"></i></a></li>
            <li><a href="{!! url('admin/registrations') !!}"><i class="glyphicon glyphicon-list-alt" aria-hidden="true" title="Registrations"></i></a></li>
            <li><a href="{!! url('admin/services') !!}"><i class="glyphicon glyphicon-phone" aria-hidden="true" title="Services"></i></a></li>
            <li><a href="{!! url('admin/payments') !!}"><i class="glyphicon glyphicon-credit-card" aria-hidden="true" title="Payments"></i></a></li>
        </ul>
    </div>

@endif