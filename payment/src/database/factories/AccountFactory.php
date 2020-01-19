<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Account::class, function (Faker $faker) {
    return [
        'client_id' => factory(App\Client::class),
        'account_name' => $faker->word,
        'iban' => $faker->word,
        'balance' => $faker->randomFloat(),
        'currency' => $faker->word,
    ];
});
