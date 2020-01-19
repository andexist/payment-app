<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Payment::class, function (Faker $faker) {
    return [
        'account_id' => factory(App\Account::class),
        'fee' => $faker->randomFloat(),
        'amount' => $faker->randomFloat(),
        'currency' => $faker->word,
        'payer_account' => $faker->word,
        'payer_name' => $faker->word,
        'receiver_account' => $faker->word,
        'receiver_name' => $faker->word,
        'details' => $faker->word,
        'status' => $faker->randomElement(['WAITING', 'REJECTED', 'APPROVED', 'COMPLETED']),
    ];
});
