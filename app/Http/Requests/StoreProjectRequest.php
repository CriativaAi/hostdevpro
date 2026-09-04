<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'type' => ['required', 'string', Rule::in([
                Project::TYPE_SAAS,
                Project::TYPE_WEBSITE,
                Project::TYPE_ECOMMERCE,
                Project::TYPE_API,
                Project::TYPE_LANDING_PAGE,
                Project::TYPE_MOBILE_APP,
            ])],
            'status' => ['required', 'string', Rule::in([
                Project::STATUS_PLANNING,
                Project::STATUS_DEVELOPMENT,
                Project::STATUS_STAGING,
                Project::STATUS_PRODUCTION,
                Project::STATUS_MAINTENANCE,
            ])],
            'repository_url' => ['nullable', 'url', 'max:255'],
            'production_url' => ['nullable', 'url', 'max:255'],
            'staging_url' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:3000'],
            'tech_stack' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome do projeto',
            'client_id' => 'cliente',
            'type' => 'tipo de projeto',
            'status' => 'status',
            'repository_url' => 'URL do repositório',
            'production_url' => 'URL de produção',
            'staging_url' => 'URL de homologação',
            'description' => 'descrição',
            'tech_stack' => 'tecnologias',
        ];
    }
}
