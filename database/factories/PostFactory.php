<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\User;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(4),
            'user_id' => User::factory(),
            'image' => $this->faker->word(),
            'description' => $this->faker->text(),
            'content' => $this->faker->paragraphs(3, true),
            'views' => $this->faker->numberBetween(-10000, 10000),
            'score' => $this->faker->randomFloat(2, 0, .99),
            'is_featured' => $this->faker->boolean(),
            'slug' => $this->faker->slug(),
            'publishet_at' => $this->faker->dateTime(),
        ];
    }
}
