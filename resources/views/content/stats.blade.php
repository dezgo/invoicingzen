@extends('web')

@section('content')
<div class="container">

    <h1>Stats</h1>
    <div class="panel panel-default">
        <div class="panel-heading">Totals</div>
        <div class="panel-body">
        Total Companies: {{ $stats['total_companies'] }}<br />
        Total Users: {{ $stats['total_users'] }}<br />
        Total Invoices: {{ $stats['total_invoices'] }}<br />
        Total Line Items: {{ $stats['total_line_items'] }}<br />
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Companies</div>
        <div class="panel-body">
            @foreach ($companies as $company)
                {{ $company->created_at }}: {{ $company->company_name }} ({{ $company->invoice_count }} invoices)<br />
            @endforeach
        </div>
    </div>
</div>

@stop
