<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Project;

class PortainerService
{
    protected $baseUrl;
    protected $apiKey;
    protected $endpointId;

    public function __construct()
    {
        $this->baseUrl = config('services.portainer.url');
        $this->apiKey = config('services.portainer.api_key');
        $this->endpointId = config('services.portainer.endpoint_id');
    }

    public function deployStack(Project $project)
    {
        $stackName = "site-{$project->uuid}";
        $hostDomain = $project->getDeploymentHost();
        $hostPath = config('services.deploy.base_path') . '/' . $stackName;

        $stackContent = $this->generateStackFile($stackName, $hostDomain, $hostPath);

        $stack = $this->findStackByName($stackName);

        if ($stack) {
            $response = $this->api()->put(
                "/api/stacks/{$stack['Id']}",
                [
                    'stackFileContent' => $stackContent,
                    'prune' => true,
                ]
            );
        } else {
            $response = $this->api()->post(
                "/api/stacks?type=1&method=string&endpointId={$this->endpointId}",
                [
                    'name' => $stackName,
                    'stackFileContent' => $stackContent,
                ]
            );
        }

        if (!$response->successful()) {
            throw new \Exception("Erro ao fazer deploy no Portainer: " . $response->body());
        }
        return $response->json();
    }

    private function generateStackFile(string $serviceName, string $hostDomain, string $hostPath): string
    {
        $stub = file_get_contents(resource_path('stubs/site-deployment.yml'));

        return Str::of($stub)
            ->replace('${SERVICE_NAME}', $serviceName)
            ->replace('${HOST_DO_CLIENTE}', $hostDomain)
            ->replace('${HOST_VOLUME_PATH}', $hostPath)
            ->toString();
    }

    private function findStackByName(string $name)
    {
        $response = $this->api()->get('/api/stacks');
        if (!$response->successful()) return null;
        
        return collect($response->json())->firstWhere('Name', $name);
    }

    private function api()
    {
        return Http::withHeaders(['X-API-Key' => $this->apiKey])
            ->baseUrl($this->baseUrl)
            ->timeout(30)
            ->retry(2, 1000); // Tenta 2x com 1s de espera
    }
}