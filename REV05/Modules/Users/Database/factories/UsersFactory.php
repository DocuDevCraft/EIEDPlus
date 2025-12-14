<?php

namespace Modules\Users\Database\factories;

use Hatamiarash7\PFaker\PFaker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UsersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Users\Entities\Users::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => PFaker::firstName(),
            'last_name' => PFaker::firstName(),
            'email' => PFaker::email(),
            'phone' => PFaker::mobile(),
            'role' => 'freelancer',
            'password' => Hash::make('12345678'),
        ];
    }
}

