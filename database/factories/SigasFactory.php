<?php

use Faker\Generator as Faker;

$factory->define(\App\Sigas::class, function (Faker $faker) {
    return [
        //
      'document_type' => $faker->randomElement(['CC', 'TI', 'CE', 'RC']),
      'first_name' => $faker->firstName,
      'second_name' => $faker->firstName,
      'fist_surname' => $faker->lastName,
      'second_surname' => $faker->lastName,
      'birthday' => $faker->date(),
      'gender' => $faker->randomElement(['M', 'F']),
      'grace_period' => $faker->randomElement([true, false]),
      'category' => $faker->randomElement(['A', 'B', 'C', 'P']),
      'contributor_id' => $faker->boolean(50) ? \App\Sigas::where('contributor_id', null)->orderByRaw("RAND()")->first()->id : null,
    ];
});
