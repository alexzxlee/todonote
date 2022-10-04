<?php

namespace Database\Factories;

use App\Models\TodoNote;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TodoNoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TodoNote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => rand(1,20),
            'content' => Str::random(50),
            'completion_time' => null,
        ];
    }
}
