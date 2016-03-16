@extends('web')

@section('content')
<div class="panel">
    <h1>Invoicing Zen Release Notes</h1>

    <h3>v1.1.4</h3>
    <h4>16 Mar 2016</h4>
    <ul>
        <li>
            Bugfix: footer overlapped text. Added breaks to master template to
            ensure footer only overlaps empty text.
        </li>
        <li>
            Create print, web, and email templates to cater for differences when
            displayed views in each of these mediums
        </li>
    </ul>

    <h3>v1.1.3</h3>
    <h4>13 Mar 2016</h4>
    <ul>
        <li>
            Add settings to allow customisation of invoice per company
        </li>
        <li>
            Addition of footer area and link to release notes page (the page
            you're reading now!)
        </li>
    </ul>

    <h3>v1.1.2</h3>
    <h4>12 Mar 2016</h4>
    <ul>
        <li>
            Remove invoice item links for non-administrators
        </li>
        <li>
            Print button now called 'View', and opens in existing window
        </li>
    </ul>

    <h3>v1.1.1</h3>
    <h4>11 Mar 2016</h4>
    <ul>
        <li>
            Add customer list sorting
        </li>
        <li>
            Add testing for select2 javascript
        </li>
        <li>
            Create template for HTTP error response pages
        </li>
    </ul>

    <h3>v1.1.0</h3>
    <h4>9 Mar 2016</h4>
    <ul>
        <li>
            Add company field. Site now setup to allow for multiple companies
            to use the invoicing system.
        </li>
        <li>
            Invoices, invoice items, settings, users, and invoice item categories
            all are specific to a company
        </li>
        <li>
            To ensure zero chance of cross-company data appearing, all queries
            are restricted to only return data for the company of current user
            via Laravel global scope mechanism
        </li>
    </ul>

    <h3>v1.0.2</h3>
    <h4>9 Mar 2016</h4>
    <ul>
        <li>
            Tickbox added to invoice items list. Designed to be used to record
            when each item is purchased. AJAX updating.
        </li>
    </ul>

    <h3>v1.0.1</h3>
    <h4>8 Mar 2016</h4>
    <ul>
        <li>
            Invoice Item now has URL field. Designed to be used to record
            vendor page for parts included in invoice and allow for quick
            navigation to that part when ready to purchase
        </li>
        <li>
            User interface tweaks
        </li>
    </ul>

    <h3>v1.0.0</h3>
    <h4>5 Mar 2016</h4>
    Initial build on invoicingzen.com domain
    <ul>
        <li>User logins</li>
        <li>Ability to create 'quote'</li>
        <li>Automatic invoice number incrementing</li>
        <li>Send invoice as PDF attachment to email</li>
        <li>Create invoice wizard</li>
        <li>Invoice item categorisation</li>
        <li>
            Administrator / user roles. Administrators can view and edit all
            invoices. Users have view-only access to their invoices.
        </li>
        <li>
            Test driven development (TDD) methodology followed providing
            comprehensive test scripts which ensures robustness of application
        </li>
    </ul>
</div>
@stop
