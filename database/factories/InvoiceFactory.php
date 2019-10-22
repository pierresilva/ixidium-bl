<?php

use Faker\Generator as Faker;

$factory->define(App\Invoice::class, function (Faker $faker) {
  $ammount = $faker->numberBetween($min = 10000, $max = 90000);
  return [
    //
    'order_id' => $faker->randomNumber(3),
    'invoice_date' => $faker->dateTime('now'),
    'due_date' => $faker->dateTime('now'),
    'tax' => round($ammount * (0.19), 2),
    'shipping' => 4000,
    'subtotal' => round(($ammount - 4000) - ($ammount * (0.19))),
    'total' => $ammount,
    'paid' => $ammount
  ];
});
