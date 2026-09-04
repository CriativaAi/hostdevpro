<?php

namespace App\Http\Requests;

use App\Models\Server;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $server = $this->route('server');
        $serverId = $server instanceof Server ? $server->id : $server;

        return [
            'name' => ['required', 'string', 'max:255'],
            'hostname' => ['nullable', 'string', 'max:255'],
            'ip_address' => ['required', 'ip', Rule::unique('servers', 'ip_address')->ignore($serverId)],
            'provider' => ['nullable', 'string', 'max:255'],
            'datacenter_location' => ['nullable', 'string', 'max:255'],
            'os' => ['nullable', 'string', 'max:255'],
            'cpu_cores' => ['required', 'integer', 'min:1', 'max:128'],
            'ram_mb' => ['required', 'integer', 'min:512', 'max:1048576'],
            'disk_gb' => ['required', 'integer', 'min:10', 'max:100000'],
            'ssh_port' => ['required', 'integer', 'min:1', 'max:65535'],
            'status' => ['required', Rule::in([Server::STATUS_ONLINE, Server::STATUS_OFFLINE, Server::STATUS_MAINTENANCE])],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
