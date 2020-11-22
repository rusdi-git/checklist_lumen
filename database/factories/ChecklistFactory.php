<?php

namespace Database\Factories;

use App\Models\Checklist;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChecklistFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Checklist::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'object_domain' => $this->faker->name,
            'object_id' => strval($this->faker->unique()->randomNumber(2)),
            'task_id'=>strval($this->faker->unique()->randomNumber(3)),
            'description'=>$this->faker->sentence(),
            'urgency'=>$this->faker->numberBetween(1,5),
            'due'=>$this->faker->dateTime(),
        ];
    }
}
