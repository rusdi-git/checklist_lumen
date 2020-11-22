<?php

namespace Database\Factories;

use App\Models\Checklist;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description'=>$this->faker->sentence(),
            'urgency'=>$this->faker->numberBetween(1,5),
            'due'=>$this->faker->dateTime(),
            'checklist_id'=> Checklist::factory(),
            'task_id'=> function (array $attributes) {return Checklist::find($attributes['checklist_id'])->task_id;},
            'assignee_id'=>$this->faker->randomNumber(3),
        ];
    }
}
