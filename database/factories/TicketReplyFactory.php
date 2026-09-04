<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketReply>
 */
class TicketReplyFactory extends Factory
{
    protected $model = TicketReply::class;

    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'user_id' => User::factory(),
            'client_id' => null,
            'author_name' => fake()->name(),
            'author_type' => TicketReply::AUTHOR_TYPE_STAFF,
            'message' => fake()->paragraph(),
            'is_internal_note' => false,
        ];
    }

    public function fromStaff(): static
    {
        return $this->state(fn (array $attributes) => [
            'author_type' => TicketReply::AUTHOR_TYPE_STAFF,
            'client_id' => null,
            'is_internal_note' => false,
        ]);
    }

    public function fromClient(): static
    {
        return $this->state(fn (array $attributes) => [
            'author_type' => TicketReply::AUTHOR_TYPE_CLIENT,
            'user_id' => null,
            'is_internal_note' => false,
        ]);
    }

    public function internalNote(): static
    {
        return $this->state(fn (array $attributes) => [
            'author_type' => TicketReply::AUTHOR_TYPE_STAFF,
            'client_id' => null,
            'is_internal_note' => true,
        ]);
    }
}
