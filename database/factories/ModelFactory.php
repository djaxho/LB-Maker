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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Team::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->words(2, true),
        'label' => $faker->sentence(),
        'about' => $faker->paragraph(),
    ];
});

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'profession' => $faker->sentence,
        'about' => $faker->paragraph,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'active' => true
    ];
});

$factory->define(App\BlogGroup::class, function (Faker\Generator $faker) {
    return [
        'user_id' => factory(App\User::class)->create()->id,
        'team_id' => factory(App\Team::class)->create()->id,
        'name' => $faker->sentence($nbWords = 3),
        'mailchimp_key' => $faker->numberBetween($min = 1000, $max = 20000),
    ];
});

$factory->define(App\Blog::class, function (Faker\Generator $faker) {
    return [
        'user_id' => factory(App\User::class)->create()->id,
        'team_id' => factory(App\Team::class)->create()->id,
        'blog_group_id' => 1,
        'name' => $faker->sentence,
        'url' => $faker->url,
        'main_text' => $faker->paragraph,
        'button_text' => $faker->sentence,
        'mailchimp_list' => $faker->word,
        'mailchimp_group' => $faker->word,
    ];
});

$factory->define(App\Leadbox::class, function (Faker\Generator $faker) {
    return [
        'user_id' => factory(App\User::class)->create()->id,
        'team_id' => factory(App\Team::class)->create()->id,
        'blog_id' => factory(App\Blog::class)->create()->id,
        'name' => $faker->sentence,
        'url' => $faker->url,
        'main_text' => $faker->paragraph,
        'button_text' => $faker->sentence,
    ];
});
