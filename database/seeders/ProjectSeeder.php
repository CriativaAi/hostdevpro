<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carlos = Client::where('email', 'carlos.menezes@alphadev.com.br')->first();
        $mariana = Client::where('email', 'mariana@nexustecnologia.io')->first();
        $rodrigo = Client::where('email', 'rodrigo@fagundesconsultoria.com.br')->first();
        $beatriz = Client::where('email', 'b.vasconcelos@startuppro.com.br')->first();

        $defaultClientId = Client::first()?->id;

        $projects = [
            [
                'client_id' => $carlos ? $carlos->id : $defaultClientId,
                'name' => 'Alpha Cloud Manager',
                'type' => Project::TYPE_SAAS,
                'status' => Project::STATUS_PRODUCTION,
                'repository_url' => 'https://github.com/CriativaAi/hostdevpro',
                'production_url' => 'https://app.hostdevpro.app.br',
                'staging_url' => 'https://staging.hostdevpro.app.br',
                'tech_stack' => 'Laravel 13, Tailwind CSS, MySQL, OpenResty, Docker',
                'description' => 'Plataforma corporativa de gestão de clientes e instâncias VPS em alta disponibilidade.',
            ],
            [
                'client_id' => $mariana ? $mariana->id : $defaultClientId,
                'name' => 'Nexus Microservices Gateway',
                'type' => Project::TYPE_API,
                'status' => Project::STATUS_DEVELOPMENT,
                'repository_url' => 'https://github.com/nexustech/api-gateway',
                'production_url' => 'https://api.nexuscloud.io',
                'staging_url' => 'https://dev-api.nexuscloud.io',
                'tech_stack' => 'PHP 8.5, Redis, PostgreSQL, Kong Gateway, Docker',
                'description' => 'Barramento de microsserviços e mensageria distribuída com autenticação JWT.',
            ],
            [
                'client_id' => $rodrigo ? $rodrigo->id : $defaultClientId,
                'name' => 'Portal Jurídico Fagundes',
                'type' => Project::TYPE_WEBSITE,
                'status' => Project::STATUS_PLANNING,
                'repository_url' => 'https://github.com/fagundes/portal',
                'production_url' => 'https://fagundesconsultoria.com.br',
                'staging_url' => null,
                'tech_stack' => 'Laravel, Tailwind CSS, Alpine.js',
                'description' => 'Website institucional responsivo com integração a agendamento de consultas e blog corporativo.',
            ],
            [
                'client_id' => $beatriz ? $beatriz->id : $defaultClientId,
                'name' => 'Startup Pro Hub - MVP',
                'type' => Project::TYPE_SAAS,
                'status' => Project::STATUS_STAGING,
                'repository_url' => 'https://github.com/startuppro/hub-app',
                'production_url' => 'https://hub.startuppro.com.br',
                'staging_url' => 'https://staging.hub.startuppro.com.br',
                'tech_stack' => 'Vue.js, Laravel, Tailwind CSS, Stripe',
                'description' => 'Comunidade e marketplace de mentoria para fundadores de startups em fase de aceleração.',
            ],
        ];

        foreach ($projects as $projectData) {
            if (!$projectData['client_id']) {
                continue;
            }

            $project = Project::withTrashed()
                ->where('client_id', $projectData['client_id'])
                ->where('name', $projectData['name'])
                ->first();

            if ($project) {
                if ($project->trashed()) {
                    $project->restore();
                }
                $project->update($projectData);
            } else {
                Project::create($projectData);
            }
        }
    }
}
