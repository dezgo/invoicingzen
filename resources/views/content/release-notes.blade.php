@extends('web')

@section('content')
<div class="panel">
    <h1>Invoicing Zen Release Notes</h1>

    <h3>v1.3.3</h3>
    <h4>25 Jun 2016</h4>
    <ul>
        <li>
            Add tabs for unpaid, receipts, and quotes (78df95b)
        </li>
    </ul>

    <h3>v1.3.2</h3>
    <h4>12 May 2016</h4>
    <ul>
        <li>
            Add help buttons to settings page (29603c6)
        </li>
    </ul>

    <h3>v1.3.1</h3>
    <h4>28 Apr 2016</h4>
    <ul>
        <li>
            Deactivate user account now has confirmation before actioning (4533121)
        </li>
        <li>
            Add jquery to pre-populate email host/port/encryption with common
            settings (d846bf8)
        </li>
    </ul>

    <h3>v1.3.0</h3>
    <h4>25 Apr 2016</h4>
    <ul>
        <li>
            Custom invoicing added. Quickly mark invoice paid/unpaid. Invoice
            list now filterable (091ca17)
        </li>
    </ul>

    <h3>v1.3.1</h3>
    <h4>26 Apr 2016</h4>
    <ul>
        <li>
            Add pricing and contact pages (cffc94d)
        </li>
    </ul>

    <h3>v1.3.0</h3>
    <h4>25 Apr 2016</h4>
    <ul>
        <li>
            Custom invoicing added. Quickly mark invoice paid/unpaid. Invoice
            list now filterable (091ca17)
        </li>
    </ul>

    <h3>v1.2.5</h3>
    <h4>20 Apr 2016</h4>
    <ul>
        <li>
            Bug fix: Issue with PDF generation needed fully-qualified path to
            logo image (f59978e)
        </li>
    </ul>

    <h3>v1.2.4</h3>
    <h4>20 Apr 2016</h4>
    <ul>
        <li>
            Remove company id field from invoice tables (dfdaa09)
        </li>
        <li>
            Change to SSL - remove references to non-secure URL helper function
            (54beadf)
        </li>
    </ul>

    <h3>v1.2.3</h3>
    <h4>19 Apr 2016</h4>
    <ul>
        <li>
            Bug fixes - email signature, email settings check now company-specific
            (342b67b)
        </li>
        <li>
            Add invoice delete button and cascade delete invoice items (9c0fb00)
        </li>
        <li>
            Add option to download invoice as PDF (49a8100)
        </li>
    </ul>

    <h3>v1.2.2</h3>
    <h4>18 Apr 2016</h4>
    <ul>
        <li>
            Company Separation fixes (32d7ebb)
        </li>
        <li>
            Add GST registered setting and update invoice (3c227cb)
        </li>
    </ul>

    <h3>v1.2.1</h3>
    <h4>17 Apr 2016</h4>
    <ul>
        <li>
            Invoice now shows correct company name. Default from address added
            to fix issue when sending password reset link (e2f738d)
        </li>
        <li>
            Separate testing of invoice print page. Remove hyperlink on logo
            (0da33b9)
        </li>
        <li>
            Add direct link to invoice to be used in emails. Create custom
            error handler (c5ea81c)
        </li>
        <li>
            From invoice list, now goes straight to print view (9895311)
        </li>
    </ul>

    <h3>v1.2.0</h3>
    <h4>16 Apr 2016</h4>
    <ul>
        <li>
            Ensure user can only view/modify records in own company. Remove Beta
            stamp (7705627)
        </li>
        <li>
            Remove next invoice number setting - easier just to calculate it
            automatically (de9a93b)
        </li>
        <li>
            More gracefully handle email errors (a816de4)
        </li>
        <li>
            Add rollbar to capture errors online (6ac6f09)
        </li>
    </ul>

    <h3>v1.1.12</h3>
    <h4>15 Apr 2016</h4>
    <ul>
        <li>
            Add email testing. Update footer to show when in development (9e935e0)
        </li>
    </ul>

    <h3>v1.1.11</h3>
    <h4>13 Apr 2016</h4>
    <ul>
        <li>
            Update invoice layout (6e80784)
        </li>
    </ul>

    <h3>v1.1.10</h3>
    <h4>10 Apr 2016</h4>
    <ul>
        <li>
            New user registration now creates new company and user automatically
            gets admin rights (8833cec)
        </li>
        <li>
            Settings now correctly works independently for users in different companies (53c226d)
        </li>
    </ul>

    <h3>v1.1.9</h3>
    <h4>9 Apr 2016</h4>
    <ul>
        <li>
            Add ability to delete invoice line items (74be6e0)
        </li>
        <li>
            Add invoice merge functionality (70579fd)
        </li>
        <li>
            Allow whole row to be clickable when opening invoice (67d43af)
        </li>
        <li>
            Add email signature setting (808ce02)
        </li>
    </ul>

    <h3>v1.1.8</h3>
    <h4>2 Apr 2016</h4>
    <ul>
        <li>
            Add invoice seeder, combine quantity and description on invoice print (e2c89ff)
        </li>
        <li>
            Remove references to customer as a security access level (7f0f1f2)
        </li>
        <li>
            Add description to home page, add beta stamp (e9f1322)
        </li>
    </ul>

    <h3>v1.1.7</h3>
    <h4>31 Mar 2016</h4>
    <ul>
        <li>
            Update email footer with new text re login options (cf538ef0)
        </li>
        <li>
            Add invoice type column to invoice list (5f3c7a6)
        </li>
        <li>
            When viewing invoice, make ready tickbox read-only for non-admins (520a452)
        </li>
        <li>
            Allow logo to be uploaded in PNG, JPG, or GIF formats and ensure
            filename is unique for each company (42016a2)
        </li>
    </ul>

    <h3>v1.1.6</h3>
    <h4>22 Mar 2016</h4>
    <ul>
        <li>
            Add total, paid, and owing on show-invoice (single invoice) page
        </li>
        <li>
            Add Client name and amount owing on show-invoices (list of all invoices) page
        </li>
        <li>
            Remove how-to-pay section when invoice already paid
        </li>
    </ul>

    <h3>v1.1.5</h3>
    <h4>16 Mar 2016</h4>
    <ul>
        <li>
            Bugfix: Javascript block for Markup / markdown buttons not appearing
        </li>
        <li>
            Add text on invoice items edit page to alert user when markup is blank
        </li>
    </ul>

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
