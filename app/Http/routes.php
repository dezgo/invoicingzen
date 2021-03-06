<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// routes accessible to anyone whether authenticated or not
Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/', 'HomeController@index')->name('home.index');
    Route::get('/contact', 'HomeController@contact')->name('home.contact');
    Route::get('/pricing', 'HomeController@pricing')->name('home.pricing');

    Route::get('/release-notes', function () {
       return view('content.release-notes');
    });

    Route::get('/view/{uuid}', 'InvoiceController@view')->name('invoice.view');
});

Route::group(['middleware' => ['web', 'superadmin']], function() {
    Route::get('/phpinfo', 'SuperAdminController@phpinfo');
    Route::get('/stats', 'SuperAdminController@stats');
});

// admin-only routes
Route::group(['middleware' => ['web', 'admin']], function() {

    // User
    Route::get('user/select', 'UserController@select')->name('user.select');
    Route::post('user/select', 'UserController@selected')->name('user.selected');
    Route::get('subscription', 'UserController@subscription_show')->name('user.subscription.show');
    Route::get('subscribe/{action}', 'UserController@subscription_update')->name('user.subscription.update');
    Route::get('payments', 'UserController@payments')->name('user.payments');
    Route::get('card', 'UserController@card_show')->name('user.card.show');
    Route::patch('card', 'UserController@card_update')->name('user.card.update');

    // Settings
    Route::get('settings', 'AdminController@show')->name('settings.show');
    Route::post('settings', 'AdminController@update')->name('settings.update');

    // Emailing
    Route::post('send', 'EmailController@send')->name('email.send');

    // Invoice item creation guided procedure
    Route::get('invoice/{invoice}/item/create1', 'InvoiceItemController@create1')->name('invoice_item.create1');
    Route::post('invoice/{invoice}/item/store1', 'InvoiceItemController@store1')->name('invoice_item.store1');
    Route::get('invoice/{invoice}/item/{category}/create2', 'InvoiceItemController@create2')->name('invoice_item.create2');
    Route::post('invoice/{invoice}/item/{category}/store2', 'InvoiceItemController@store2')->name('invoice_item.store2');

    // Invoice item
    Route::resource('invoice_item', 'InvoiceItemController');
    Route::get('invoice_item/{invoice_item}/create', 'InvoiceItemController@create')->name('invoice_item.create');
    Route::post('invoice_item/{invoice_item}/store', 'InvoiceItemController@store')->name('invoice_item.store');
    Route::get('invoice_item/{invoice_item}/delete', 'InvoiceItemController@delete');
    Route::post('invoice_item/{invoice_item}/ready', 'InvoiceItemController@ready');

    // Invoice item category
    Route::resource('invoice_item_category', 'InvoiceItemCategoryController');
    Route::get('invoice_item_category/{invoice_item_category}/delete', 'InvoiceItemCategoryController@delete');
});

// routes accessible to all authenticated users
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('user/{user}/delete', 'UserController@confirm_delete')->name('user.delete');
    Route::resource('user', 'UserController');

    // Invoice
    Route::resource('invoice', 'InvoiceController');
    Route::get('invoice/{invoice}/print', 'InvoiceController@prnt');
    Route::post('invoice/{invoice}/pay', 'InvoiceController@pay')->name('invoice.pay');
    Route::get('invoice/{invoice}/pdf', 'InvoiceController@generate_pdf');
});

// routes only accessible to administrators
Route::group(['middleware' => ['web', 'admin']], function() {
    Route::get('invoice/{customer}/create', 'InvoiceController@create'); // create invoice for given customer
    Route::get('invoice/{invoice}/delete', 'InvoiceController@delete');
    Route::get('invoice/{invoice}/email', 'EmailController@showComposeEmailView');
    Route::get('invoice/{invoice}/merge', 'InvoiceController@selectmerge');
    Route::get('invoice/{invoice}/pay', 'InvoiceController@markPaid');
    Route::get('invoice/{invoice}/unpay', 'InvoiceController@markUnpaid');
    Route::post('invoice/merge', 'InvoiceController@domerge');
});

Route::group(['middleware' => ['web', 'admin', 'premium']], function() {
    Route::get('invoice_template/defaults', 'InvoiceTemplateController@defaults');
    Route::post('invoice_template/defaults', 'InvoiceTemplateController@defaults_force');
    Route::resource('invoice_template', 'InvoiceTemplateController');
    Route::get('invoice_template/{invoice_template}/delete', 'InvoiceTemplateController@delete')->name('invoice_template.delete');
});

Route::post(
    'stripe/webhook',
    'WebhookController@handleWebhook'
);
