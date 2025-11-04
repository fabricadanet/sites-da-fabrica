<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CPanelService
{
    protected $baseUrl;
    protected $apiToken;
    protected $mainDomain;
    protected $username;

    public function __construct()
    {
        $config = config('services.cpanel');
        $this->baseUrl = $config['host'];
        $this->username = $config['username'];
        $this->apiToken = $config['api_token'];
        $this->mainDomain = $config['main_domain'];
    }

    /**
     * Faz o provisionamento do domínio no cPanel (Sub ou Addon)
     * e retorna o caminho relativo para o deploy (ex: public_html/dominio.com)
     */
    public function provisionDomain(Project $project): string
    {
        $host = $project->getDeploymentHost(); // Ex: cliente.sitesdafabrica.com.br ou www.cliente.com
        
        // Caminho onde os arquivos serão salvos (relativo ao /home/user)
        $relativePath = 'public_html/' . $host;

        if ($project->custom_domain) {
            // É um Domínio de Complemento (Addon Domain)
            $this->createAddonDomain($project->custom_domain, $relativePath);
        } else {
            // É um Subdomínio
            $this->createSubdomain($project->subdomain, $relativePath);
        }

        return $relativePath;
    }

    private function createSubdomain(string $fullSubdomain, string $relativePath)
    {
        // $fullSubdomain é 'cliente.sitesdafabrica.com.br'
        // $this->mainDomain é 'sitesdafabrica.com.br'
        $prefix = Str::before($fullSubdomain, '.' . $this->mainDomain); // 'cliente'

        return $this->apiCall('SubDomain', 'addsubdomain', [
            'domain'     => $prefix,
            'rootdomain' => $this->mainDomain,
            'dir'        => $relativePath,
        ]);
    }

    private function createAddonDomain(string $newDomain, string $relativePath)
    {
        // cPanel exige um "subdomínio" interno para o addon domain
        // Vamos criar um a partir do domínio: www.cliente.com -> cliente
        $subdomainPrefix = Str::of($newDomain)->remove('www.')->slug()->limit(10);
        
        return $this->apiCall('AddonDomain', 'addaddondomain', [
            'newdomain' => $newDomain,
            'subdomain' => $subdomainPrefix,
            'dir'       => $relativePath,
        ]);
    }

    /**
     * Helper para fazer chamadas à cPanel UAPI
     */
    private function apiCall(string $module, string $function, array $params = [])
    {
        $url = "{$this->baseUrl}/execute/{$module}/{$function}";

        $response = Http::withHeaders([
            'Authorization' => 'cpanel ' . $this->username . ':' . $this->apiToken,
        ])->get($url, $params);

        if (!$response->successful()) {
            Log::error('Falha na API cPanel', ['response' => $response->body()]);
            throw new \Exception("Erro na API cPanel: " . $response->body());
        }

        $data = $response->json();

        if (isset($data['errors']) && $data['errors'] !== null) {
            // Ignora erro "já existe", pois queremos que seja idempotente
            if (Str::contains($data['errors'][0], 'already exists')) {
                Log::warning('Domínio já existe no cPanel, continuando deploy.', $params);
                return true;
            }
            Log::error('Erro na API cPanel', ['response' => $data]);
            throw new \Exception("Erro na API cPanel: " . $data['errors'][0]);
        }
        
        Log::info('API cPanel executada com sucesso', ['module' => $module, 'function' => $function]);
        return true;
    }
}