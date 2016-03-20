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
    // Route::match(['put', 'patch'], 'profile/update', 'Auth\AuthController@update');
    // Route::post('profile/edit', 'Auth\AuthController@update');
    // Route::get('profile/edit', 'Auth\AuthController@edit');
    Route::get('/', function () {
       return view('content.index');
    });

    Route::get('/release-notes', function () {
       return view('content.release-notes');
    });

});

Route::group(['middleware' => ['web', 'superadmin']], function() {
    Route::get('/phpinfo', 'SuperAdminController@phpinfo');
});

// admin-only routes
Route::group(['middleware' => ['web', 'admin']], function() {

    // select customer as first step when creating invoice
    Route::get('user/select', 'UserController@select')->name('user.select');
    Route::post('user/select', 'UserController@selected')->name('user.selected');

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
    Route::resource('user', 'UserController');

    // Invoice
    Route::resource('invoice', 'InvoiceController');
    Route::get('invoice/{invoice}/print', 'InvoiceController@prnt');
    Route::post('invoice/{invoice}/pay', 'InvoiceController@pay')->name('invoice.pay');
});

Route::group(['middleware' => ['web', 'admin']], function() {
    Route::get('invoice/{customer}/create', 'InvoiceController@create'); // create invoice for given customer
    Route::get('invoice/{invoice}/delete', 'InvoiceController@delete');
    Route::get('invoice/{invoice}/email', 'InvoiceController@email')->name('invoice.email');
});
