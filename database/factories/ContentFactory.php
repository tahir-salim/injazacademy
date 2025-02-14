<?php

namespace Database\Factories;

use App\Models\Content;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Content::class;
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "title" => $this->faker->word(9),
            "type" => $this->faker->randomElements(['FILE', 'VIDEO']),
            "url" =>  $this->faker->ean13(),
            "data" => $this->faker->url(),
            "status" => Content::PUBLISHED,
            "order_number" => rand(0, 20),
            "duration" => '2h 30m',
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ];
    }
}
