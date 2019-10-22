<?php

use Faker\Generator as Faker;
use App\Helpers\SlugHelper;

$factory->define(\pierresilva\Sentinel\Models\Permission::class, function (Faker $faker) {
    $name = $faker->words(2, true);
    return [
        //
        'name' => $name,
        'slug' => SlugHelper::uniqueSlug($name, 'permissions', '-'),
        'description' => $faker->paragraph,
    ];
});
