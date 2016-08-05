<nav class="navbar navbar-default hidden-print">
    <div class="container-fluid">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                data-target="#app-navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="/">
                <!-- <i class="glyphicon glyphicon-home">&nbsp;</i> -->
                Invoicing Zen
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                            <li><a href="/invoice" name='invoicesAnchor'>Invoices</a></li>

            @if(Gate::check('create-invoice'))
                            <li><a href="/invoice_item_category" name='invoiceItemCategoriesAnchor'>
                                Invoice Item Categories</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/user/select" name='createInvoiceAnchor'>
                                Create Invoice</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/settings" name='settingsAnchor'>Settings</a></li>
            @endif

            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                Tools<span class="caret"></span>
            </a>

            <ul class="dropdown-menu" role="menu">
                <li><a href="/tools/accruals">Accrual Generator</a></li>
                <li><a href="/tools/allocate_prepayment">Allocate Prepayment</a></li>
            </ul>
            </li>

            @if(Gate::check('admin'))
                            <li><a href="/user" name='userAnchor'>Users</a></li>
            @endif
            @if(Gate::check('premium'))
                            <li><a href="/invoice_template" name='userAnchor'>Templates</a></li>
            @endif
            @if(Gate::check('super-admin'))
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                Admin<span class="caret"></span>
            </a>

            <ul class="dropdown-menu" role="menu">
                <li><a href="/phpinfo">PHP Info</a></li>
                <li><a href="/stats">Stats</a></li>
            </ul>
            </li>
            @endif
                </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->full_name }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ '/user/'.Auth::user()->id.'/edit' }}">Edit Profile</a></li>
                        <li><a href="/logout" name='logoutAnchor'><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
