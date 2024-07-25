<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Auth\SentinelUser;
use App\Models\Individual\Individual;
use Illuminate\Support\Str;
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
$departments = collect(config('constant.department_options'));
$factory->define(SentinelUser::class, function (Faker $faker) use ($departments) {
    return [
        'email' => $faker->unique()->safeEmail,
        'password' => 'test@123',
        'first_name' => $faker->firstName,
        'middle_name' => rand(0, 1) ? substr($faker->lastName, 0, 1) : null,
        'last_name' => $faker->lastName,
        'country_code' => "+1",
        'phone_number' => $faker->unique()->regexify('^\d{10}$'),
        'department' => $departments->random(),
        'title' => collect(config('constant.designation_options'))->random(),
    ];
});

$factory->define(Individual::class, function (Faker $faker){
    $first_name = $faker->firstName;
    $last_name = $faker->lastName;

    $full_name = $first_name.' '.$last_name;

    return [
        'full_name' => $full_name,
        'company_name' => $faker->company,
        'story' => $faker->realText(100,3),
        'country' => $faker->country,
        'city'      => $faker->city,
        'years_of_experience' => rand(0, 20),
        'mood' => rand(1, 9),
        'banned' => 0,
        'job_title_id' => rand(1, 50),
        'industry_id'=> rand(1, 50),
        'academic_level_id'=> rand(1, 50)
    ];
});
