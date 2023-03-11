<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = News::class;

    public function definition()
    {
        $images = [
            'https://source.unsplash.com/XbwHrt87mQ0',
            'https://source.unsplash.com/z9fFOzL5L_Y',
            'https://source.unsplash.com/ITzfgP77DTg',
            'https://source.unsplash.com/sBjIRDC0H5Q',
            'https://source.unsplash.com/wtzOhxEX4WU',
            'https://source.unsplash.com/2zfL4pyw3pY',
        ];
        return [
            'title' => $this->faker->name,
            'description' => $this->faker->text,
            'image' => $images[array_rand($images)],
            'banner' => $images[array_rand($images)],
            'category_id' => 1,
            'language_id' => 1,
            'published_date' => now(),
        ];
    }
}
