<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $array = ['ACT', 'NSW', 'SA', 'NT', 'WA', 'TAS', 'QLD', 'VIC'];
    $state = $faker->randomElement($array);
    return [
        'company_id' => 1,
        'first_name' => $faker->firstname,
        'last_name' => $faker->lastname,
        'email' => $faker->email,
        'password' => bcrypt('password'),
        'remember_token' => str_random(10),
        'address1' => $faker->streetAddress,
        'suburb' => $faker->city,
        'state' => $state,
        'postcode' => rand(1000,9999),
    ];
});

$factory->define(App\Invoice::class, function (Faker\Generator $faker) {
    // note the following will persist a customer to the database, so will
    // need to clear the database now to get rid of this record
    return [
        'customer_id' => factory(App\User::class)->create()->id,
    ];
});


$factory->define(App\InvoiceItem::class, function (Faker\Generator $faker) {
    // note the following will persist an invoice to the database, so will
    // need to clear the database now to get rid of this record
    return [
        'description' => $faker->word,
        'quantity' => $faker->numberBetween(1,10),
        'price' => $faker->randomFloat(2,1,500),
        'category_id' => App\InvoiceItemCategory::orderByRaw("RAND()")->first()->id,
    ];
});

$factory->define(App\Company::class, function (Faker\Generator $faker) {
    return [
        'subdomain' => $faker->word,
        'company_name' => $faker->company,
    ];
});
