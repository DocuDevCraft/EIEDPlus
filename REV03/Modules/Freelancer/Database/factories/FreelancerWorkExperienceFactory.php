<?php

namespace Modules\Freelancer\Database\factories;

use Hatamiarash7\PFaker\PFaker;
use Illuminate\Database\Eloquent\Factories\Factory;

class FreelancerWorkExperienceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Freelancer\Entities\FreelancerWorkExperience::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $ActivityType = array('پاره وقت', 'مالک', 'تمام وقت', 'پیمانکار');
        $faker = \Faker\Factory::create();

        return [
            'activity_type' => $ActivityType[array_rand($ActivityType)],
            'post' => PFaker::word(),
            'field' => PFaker::jobTitle(),
            'company' => 'شرکت ' . PFaker::firstName(),
            'at_time' => PFaker::birthday(),
            'to_time' => PFaker::birthday(),
            'country' => 'ایران',
            'address' => PFaker::address(),
            'phone' => PFaker::telephone(),
            'website' => $faker->domainName,
        ];
    }
}

