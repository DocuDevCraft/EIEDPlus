<?php

namespace Modules\Freelancer\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FreelancerLanguageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Freelancer\Entities\FreelancerLanguage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $Language = array("انگلیسی", "آلمانی", "ایتالیایی", "عربی", 'ترکی');

        return [
            'language_name' => $Language[array_rand($Language)],
            'language_level' => 'متوسط'
        ];
    }
}

