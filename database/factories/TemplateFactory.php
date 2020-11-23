<?php

namespace Database\Factories;

use App\Models\Template;
use App\Models\TemplateItem;
use Illuminate\Database\Eloquent\Factories\Factory;


class TemplateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Template::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description'=>$this->faker->sentence(),
            'due_interval'=>$this->faker->numberBetween(1,5),
            'due_unit'=>'day',
        ];
    }
}


class TemplateItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TemplateItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description'=>$this->faker->sentence(),
            'due_interval'=>$this->faker->numberBetween(1,5),
            'due_unit'=>'day',
            'template_id'=>Template::factory()
        ];
    }
}