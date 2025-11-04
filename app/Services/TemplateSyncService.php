<?php

namespace App\Services;

use App\Models\Template;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;

class TemplateSyncService
{
    protected string $localRepoPath;
    protected string $storagePath;
    protected string $repoUrl = 'https://github.com/rdrgzma/sites-saas.git'; // Mude se for privado

    public function __construct()
    {
        // Onde o 'git clone' será feito
        $this->localRepoPath = storage_path('app/templates-repo');
        // Onde os arquivos (blade/assets) serão copiados para uso
        $this->storagePath = storage_path('app/templates');
    }

    public function sync(): array
    {
        $this->cloneOrPullRepo();

        $manifestPath = $this->localRepoPath . '/manifest.json';
        if (!File::exists($manifestPath)) {
            Log::error('manifest.json não encontrado no repositório.');
            return ['status' => 'error', 'message' => 'manifest.json não encontrado.'];
        }

        $manifest = json_decode(File::get($manifestPath), true);
        $syncedTemplates = [];

        // Prepara o diretório de templates "vivos"
        if (!File::exists($this->storagePath)) {
            File::makeDirectory($this->storagePath);
        }

        foreach ($manifest['templates'] ?? [] as $templateData) {
            if ($templateData['status'] !== 'ativo') {
                continue;
            }

            $templateName = $templateData['id'];
            $templateRepoPath = $this->localRepoPath . $templateData['path'];
            
            // 1. Copia os arquivos (Blade, Assets) para o storage
            $templateDestPath = $this->storagePath . '/' . $templateName;
            File::deleteDirectory($templateDestPath); // Limpa antes de copiar
            File::copyDirectory($templateRepoPath, $templateDestPath);

            // 2. Lê o schema.json
            $schemaPath = $templateDestPath . '/schema.json';
            if (!File::exists($schemaPath)) {
                Log::warning("schema.json não encontrado para o template: $templateName");
                continue;
            }
            $schema = json_decode(File::get($schemaPath), true);

            // 3. Atualiza ou Cria no Banco de Dados
            Template::updateOrCreate(
                ['name' => $templateName],
                [
                    'display_name' => $schema['name'] ?? $templateName,
                    'description' => $schema['description'] ?? null,
                    'category' => $schema['category'] ?? 'Geral',
                    'status' => 'active',
                    'github_path' => $templateData['path'],
                    'schema' => $schema,
                ]
            );
            $syncedTemplates[] = $templateName;
        }
        
        Template::whereNotIn('name', $syncedTemplates)->update(['status' => 'inactive']);
        Log::info('Sincronização de templates concluída.', $syncedTemplates);
        return ['status' => 'success', 'synced' => $syncedTemplates];
    }

    protected function cloneOrPullRepo()
    {
        if (File::exists($this->localRepoPath . '/.git')) {
            Log::info('Atualizando repositório de templates...');
            Process::path($this->localRepoPath)->run('git reset --hard && git pull origin main');
        } else {
            Log::info('Clonando repositório de templates...');
            File::deleteDirectory($this->localRepoPath);
            Process::run("git clone {$this->repoUrl} {$this->localRepoPath}");
        }
    }
}