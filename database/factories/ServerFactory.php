<?php

namespace Database\Factories;

use App\Models\Server;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Server>
 */
class ServerFactory extends Factory
{
    protected $model = Server::class;

    public function definition(): array
    {
        return [
            'name' => 'VPS ' . fake()->company() . ' Node',
            'hostname' => fake()->domainWord() . '.hostdevpro.app.br',
            'ip_address' => fake()->unique()->ipv4(),
            'provider' => fake()->randomElement(['Integrator Host', 'Hetzner', 'AWS', 'DigitalOcean']),
            'datacenter_location' => fake()->randomElement(['São Paulo - BR', 'Falkenstein - DE', 'Virgínia - US']),
            'os' => 'Ubuntu 24.04 LTS',
            'cpu_cores' => fake()->randomElement([2, 4, 8]),
            'ram_mb' => fake()->randomElement([4096, 8192, 16384]),
            'disk_gb' => fake()->randomElement([80, 160, 320]),
            'ssh_port' => 22,
            'status' => Server::STATUS_ONLINE,
            'notes' => fake()->sentence(),
        ];
    }

    public function online(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Server::STATUS_ONLINE,
        ]);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Server::STATUS_MAINTENANCE,
        ]);
    }

    public function offline(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Server::STATUS_OFFLINE,
        ]);
    }
}
