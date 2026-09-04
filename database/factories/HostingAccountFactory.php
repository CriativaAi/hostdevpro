<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Server;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HostingAccount>
 */
class HostingAccountFactory extends Factory
{
    protected $model = HostingAccount::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'server_id' => Server::factory(),
            'domain' => fake()->unique()->domainName(),
            'username' => 'usr_' . fake()->unique()->lexify('??????'),
            'plan' => fake()->randomElement([HostingAccount::PLAN_BASIC, HostingAccount::PLAN_PRO, HostingAccount::PLAN_ENTERPRISE]),
            'php_version' => fake()->randomElement(['8.2', '8.3', '8.4', '8.5']),
            'disk_quota_mb' => 5120,
            'disk_used_mb' => fake()->numberBetween(200, 4500),
            'bandwidth_quota_mb' => 50000,
            'ssl_status' => HostingAccount::SSL_ACTIVE,
            'status' => HostingAccount::STATUS_ACTIVE,
            'suspended_reason' => null,
            'notes' => fake()->sentence(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => HostingAccount::STATUS_ACTIVE,
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => HostingAccount::STATUS_SUSPENDED,
            'suspended_reason' => 'Suspensão por falta de pagamento ou uso excessivo de recursos.',
        ]);
    }
}
