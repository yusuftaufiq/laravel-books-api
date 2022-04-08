<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonalAccessToken>
 */
class PersonalAccessTokenFactory extends Factory
{
    public const DEFAULT_PLAIN_TEXT_TOKEN = 'NYMsLiXkCnPkIMt6lSZWL6X8EJsYaxmmfn2AJO8B';

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'token' => hash(algo: 'sha256', data: static::DEFAULT_PLAIN_TEXT_TOKEN),
            'abilities' => ['*'],
            'expired_at' => Carbon::createFromInterface($this->faker->dateTimeBetween('today', '+1 month')),
        ];
    }
}
