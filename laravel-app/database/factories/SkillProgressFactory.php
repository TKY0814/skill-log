<?php

namespace Database\Factories;

use App\Models\Skill;
use App\Models\SkillProgress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SkillProgress>
 */
class SkillProgressFactory extends Factory
{
    protected $model = SkillProgress::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'skill_id' => Skill::factory(),
            'progress_date' => $this->faker->date(),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
        ];
    }
}

