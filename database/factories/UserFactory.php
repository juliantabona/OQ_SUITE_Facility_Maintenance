<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

/*
$factory->define(App\Company::class, function ($faker) {
    return [
        'name' => $faker->company,
        'description' => $faker->catchPhrase,
        'city' => $faker->city,
        'state_or_region' => $faker->state,
        'address' => $faker->paragraddressaph,
        'industry' => $faker->paragraph,
        'type' => $faker->paragraph,
        'website_link' => $faker->paragraph,
        'profile_doc_url' => $faker->paragraph,
        'phone_id' => $faker->paragraph,
        'phone_id' => $faker->paragraph,
        'email' => $faker->paragraph,
        'logo_url' => $faker->paragraph,
    ];
});
*/

$factory->define(App\User::class, function (Faker $faker) use ($factory) {
    $gender = $faker->randomElements(['male', 'female']);

    return [
        'first_name' => $faker->firstName($gender),
        'last_name' => $faker->lastName,
        'gender' => $gender,
        'date_of_birth' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'bio' => $faker->text,
        'address' => $faker->address,
        'phone_ext' => $faker->numberBetween(260, 270),
        'phone_num' => $faker->phoneNumber,
        'email' => $faker->safeEmail,
        'additional_email' => $faker->companyEmail,
        'username' => $faker->userName,
        'password' => '$2y$10$GUKtWT.VV7Iip6ZSDd2ac.QWtmSfCls0uMIApgtKdVk1o6hDIrCeu',
        'verified' => 1,
        'company_branch_id' => $factory->create(App\User::class)->id,
        'position' => $faker->jobTitle,
        'country' => $faker->country,
        'city' => $faker->city,
        'accessibility' => $faker->name,
    ];
});
