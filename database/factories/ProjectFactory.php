<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'name' => fake()->words(3, true),
            'type' => fake()->randomElement([
                Project::TYPE_SAAS,
                Project::TYPE_WEBSITE,
                Project::TYPE_ECOMMERCE,
                Project::TYPE_API,
                Project::TYPE_LANDING_PAGE,
                Project::TYPE_MOBILE_APP,
            ]),
            'status' => fake()->randomElement([
                Project::STATUS_PLANNING,
                Project::STATUS_DEVELOPMENT,
                Project::STATUS_STAGING,
                Project::STATUS_PRODUCTION,
                Project::STATUS_MAINTENANCE,
            ]),
            'repository_url' => fake()->optional()->url(),
            'production_url' => fake()->optional()->url(),
            'staging_url' => fake()->optional()->url(),
            'description' => fake()->paragraph(),
            'tech_stack' => 'Laravel, Tailwind CSS, MySQL, Docker',
        ];
    }

    /**
     * Indicate that the project is in production.
     */
    public function inProduction(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Project::STATUS_PRODUCTION,
        ]);
    }

    /**
     * Indicate that the project is in development.
     */
    public function inDevelopment(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Project::STATUS_DEVELOPMENT,
        ]);
    }
}
