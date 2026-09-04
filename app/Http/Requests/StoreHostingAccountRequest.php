<?php

namespace App\Http\Requests;

use App\Models\HostingAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHostingAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'server_id' => ['required', 'exists:servers,id'],
            'domain' => ['required', 'string', 'max:255', 'regex:/^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,}$/i', 'unique:hosting_accounts,domain'],
            'username' => ['nullable', 'string', 'max:50'],
            'plan' => ['required', Rule::in([HostingAccount::PLAN_BASIC, HostingAccount::PLAN_PRO, HostingAccount::PLAN_ENTERPRISE])],
            'php_version' => ['required', 'string', 'max:10'],
            'disk_quota_mb' => ['required', 'integer', 'min:512', 'max:1000000'],
            'disk_used_mb' => ['nullable', 'integer', 'min:0'],
            'bandwidth_quota_mb' => ['required', 'integer', 'min:1000', 'max:10000000'],
            'ssl_status' => ['required', Rule::in([HostingAccount::SSL_ACTIVE, HostingAccount::SSL_PENDING, HostingAccount::SSL_EXPIRED, HostingAccount::SSL_NONE])],
            'status' => ['required', Rule::in([HostingAccount::STATUS_ACTIVE, HostingAccount::STATUS_SUSPENDED, HostingAccount::STATUS_PENDING, HostingAccount::STATUS_TERMINATED])],
            'suspended_reason' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
