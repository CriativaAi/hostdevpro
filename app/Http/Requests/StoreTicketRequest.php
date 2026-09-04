<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'hosting_account_id' => ['nullable', 'exists:hosting_accounts,id'],
            'server_id' => ['nullable', 'exists:servers,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'department' => [
                'required',
                Rule::in([
                    Ticket::DEPARTMENT_TECHNICAL,
                    Ticket::DEPARTMENT_FINANCIAL,
                    Ticket::DEPARTMENT_COMMERCIAL,
                    Ticket::DEPARTMENT_DEVOPS,
                ]),
            ],
            'priority' => [
                'required',
                Rule::in([
                    Ticket::PRIORITY_LOW,
                    Ticket::PRIORITY_MEDIUM,
                    Ticket::PRIORITY_HIGH,
                    Ticket::PRIORITY_URGENT,
                ]),
            ],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:10000'],
        ];
    }
}
