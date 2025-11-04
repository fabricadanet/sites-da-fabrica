<?php

namespace App\Jobs;

use App\Models\Project;
use App\Services\CPanelService; // MUDOU
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

class PublishSiteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Project $project) {}

    // Injeta o novo CPanelService
    public function handle(CPanelService $cpanel)
    {
        $this->project->load('template');
        $this->project->update(['status' => 'publishing']);
        
        $template = $this->project->template;
        $data = $this->project->json_data;

        try {
            // --- Etapa 1: Provisionar o Domínio no cPanel ---
            // Isso cria o Subdomínio ou Addon Domain e nos retorna o caminho
            // Ex: "public_html/www.cliente.com"
            $relativePath = $cpanel->provisionDomain($this->project);
            Log::info("Domínio provisionado. Caminho: $relativePath", ['project' => $this->project->id]);

            // --- Etapa 2: Renderizar HTML ---
            $templateName = $template->name;
            $templateBladePath = storage_path("app/templates/{$templateName}/index.blade.php");

            if (!file_exists($templateBladePath)) {
                throw new \Exception("Blade não encontrado: $templateBladePath");
            }

            $bladeContent = file_get_contents($templateBladePath);
            $html = Blade::render($bladeContent, $data);

            // --- Etapa 3: Enviar arquivos via SFTP ---
            // O disco 'deploy' está na raiz (ex: /home/fabricad)
            $disk = Storage::disk('deploy');

            // Limpa o diretório antes de enviar (opcional, mas bom)
            $disk->deleteDirectory($relativePath);
            $disk->makeDirectory($relativePath);
            
            // Envia o index.html
            $disk->put("{$relativePath}/index.html", $html);
            Log::info("index.html enviado para: $relativePath", ['project' => $this->project->id]);


            // --- Etapa 3b: Copiar /assets do template ---
            $localAssetsPath = storage_path("app/templates/{$templateName}/assets");
            if (File::isDirectory($localAssetsPath)) {
                $files = File::allFiles($localAssetsPath);
                foreach ($files as $file) {
                    $assetRelativePath = $file->getRelativePathname();
                    // Envia o arquivo para "public_html/dominio.com/assets/css/style.css"
                    $disk->putFileAs("{$relativePath}/assets", $file->getPathname(), $assetRelativePath);
                }
                Log::info(count($files) . " assets enviados.", ['project' => $this->project->id]);
            }
            
            // --- Etapa 3c: Copiar uploads do usuário (Futuro) ---

            // --- Etapa 4: Concluir ---
            $this->project->update(['status' => 'published']);
            Log::info("Deploy concluído com sucesso!", ['project' => $this->project->id]);

        } catch (\Exception $e) {
            $this->project->update(['status' => 'failed']);
            Log::error("Falha no PublishSiteJob (cPanel): " . $e->getMessage(), ['project_id' => $this->project->id]);
            report($e);
            throw $e;
        }
    }
}