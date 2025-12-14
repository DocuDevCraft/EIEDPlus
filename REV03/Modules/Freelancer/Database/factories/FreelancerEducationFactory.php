<?php

namespace Modules\Freelancer\Database\factories;

use Faker\Factory as Faker;
use Hatamiarash7\PFaker\PFaker;
use Illuminate\Database\Eloquent\Factories\Factory;

class FreelancerEducationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Freelancer\Entities\FreelancerEducation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = Faker::create();
        return [
            'field_of_study' => PFaker::word(),
            'orientation' => PFaker::word(),
            'education_level' => 'کارشناسی',
            'university' => 'دانشگاه آزاد ' . PFaker::state(),
            'at_time' => PFaker::birthday(),
            'to_time' => PFaker::birthday(),
            'country' => 'ایران',
            'city' => PFaker::city(),
            'gpa' => $faker->numberBetween(12, 20),
        ];
    }
}

