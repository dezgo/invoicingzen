@if (Session::has('status-success'))
<div class="alert alert-success alert-dismissible" role="alert" id="success-alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    {{ Session::get('status-success') }}
</div>
@endif

@if (Session::has('status-warning'))
<div class="alert alert-warning alert-dismissible" role="alert" id="warning-alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    {{ Session::get('status-warning') }}
</div>
@endif
