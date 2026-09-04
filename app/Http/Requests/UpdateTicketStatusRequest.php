<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'nullable',
                Rule::in([
                    Ticket::STATUS_OPEN,
                    Ticket::STATUS_IN_PROGRESS,
                    Ticket::STATUS_ANSWERED,
                    Ticket::STATUS_CUSTOMER_REPLY,
                    Ticket::STATUS_CLOSED,
                ]),
            ],
            'priority' => [
                'nullable',
                Rule::in([
                    Ticket::PRIORITY_LOW,
                    Ticket::PRIORITY_MEDIUM,
                    Ticket::PRIORITY_HIGH,
                    Ticket::PRIORITY_URGENT,
                ]),
            ],
            'user_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
