<?php

namespace Modules\Freelancer\Database\factories;

use Faker\Factory as Faker;
use Hatamiarash7\PFaker\PFaker;
use Illuminate\Database\Eloquent\Factories\Factory;

class FreelancerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Freelancer\Entities\Freelancer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = Faker::create();
        $RandomMeliCode = PFaker::code_melli();

        return [
            'meli_code' => $RandomMeliCode,
            'shenasnameh' => $RandomMeliCode,
            'mahale_sodoor' => PFaker::city(),
            'country' => 'ایران',
            'address' => PFaker::address(),
            'sarbazi' => 'معاف',
            'website' => $faker->url(),
            'linkedin' => $faker->url(),
            'birthday' => PFaker::birthday(),
            'home_phone' => PFaker::telephone(),
            'postal_code' => PFaker::code_melli(),
            'biography' => PFaker::paragraph(),
            'tax' => true,
            'tax_value' => rand(1, 9),
            'shaba' => $faker->numberBetween(111111111111111111111111, 199999999999999999999999),
            'accepted_rules' => true,
            'grade' => rand(7, 9),
        ];
    }
}

