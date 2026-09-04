<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'ticket_number' => 'HDP-' . date('Y') . '-' . fake()->unique()->numberBetween(1000, 9999),
            'client_id' => Client::factory(),
            'user_id' => User::factory(),
            'hosting_account_id' => null,
            'server_id' => null,
            'project_id' => null,
            'department' => fake()->randomElement([
                Ticket::DEPARTMENT_TECHNICAL,
                Ticket::DEPARTMENT_FINANCIAL,
                Ticket::DEPARTMENT_COMMERCIAL,
                Ticket::DEPARTMENT_DEVOPS,
            ]),
            'priority' => fake()->randomElement([
                Ticket::PRIORITY_LOW,
                Ticket::PRIORITY_MEDIUM,
                Ticket::PRIORITY_HIGH,
                Ticket::PRIORITY_URGENT,
            ]),
            'status' => fake()->randomElement([
                Ticket::STATUS_OPEN,
                Ticket::STATUS_IN_PROGRESS,
                Ticket::STATUS_ANSWERED,
                Ticket::STATUS_CUSTOMER_REPLY,
                Ticket::STATUS_CLOSED,
            ]),
            'subject' => fake()->sentence(5),
            'last_reply_at' => now(),
            'closed_at' => null,
        ];
    }

    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Ticket::STATUS_OPEN,
            'closed_at' => null,
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Ticket::STATUS_CLOSED,
            'closed_at' => now(),
        ]);
    }

    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => Ticket::PRIORITY_URGENT,
        ]);
    }
}
