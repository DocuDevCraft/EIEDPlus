<?php

namespace Modules\Freelancer\Database\factories;

use Hatamiarash7\PFaker\PFaker;
use Illuminate\Database\Eloquent\Factories\Factory;

class FreelancerCoursesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Freelancer\Entities\FreelancerCourses::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' =>  PFaker::jobTitle(),
            'academy' =>  'موسسه مرکزی ' . PFaker::city(),
            'at_time' =>  PFaker::birthday(),
            'to_time' =>  PFaker::birthday(),
        ];
    }
}

