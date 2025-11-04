<?php

namespace App\Console\Commands;

use App\Services\TemplateSyncService;
use Illuminate\Console\Command;

class SyncTemplates extends Command
{
    protected $signature = 'templates:sync';
    protected $description = 'Sincroniza os templates do repositório GitHub com o banco de dados e o storage local.';

    public function handle(TemplateSyncService $syncService)
    {
        $this->info('Iniciando sincronização de templates...');
        
        try {
            $result = $syncService->sync();
            
            if ($result['status'] === 'success') {
                $this->info('Sincronização concluída com sucesso!');
                $this->comment('Templates sincronizados: ' . implode(', ', $result['synced']));
            } else {
                $this->error('Erro na sincronização: ' . $result['message']);
            }
        } catch (\Exception $e) {
            $this->error('Uma exceção ocorreu: ' . $e->getMessage());
            report($e);
        }
        return 0;
    }
}