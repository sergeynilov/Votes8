<?php

use Faker\Generator as Faker;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use App\VoteCategory;

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

$factory->define(App\VoteCategory::class, function (Faker $faker) {

    $name= 'Vote category ' . $faker->word;
    $slug = SlugService::createSlug(VoteCategory::class, 'slug', $name);

    return [
        'name' => $name,
        'slug' => $slug,
        'active' => true,
        'in_subscriptions' => true,
        'meta_description' => $faker->text,
        'meta_keywords' => $faker->words(4),
    ];
});