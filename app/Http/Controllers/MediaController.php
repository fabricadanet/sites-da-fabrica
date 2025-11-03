<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\GdDriver;

class MediaController extends Controller
{
    /**
     * Listar mídia de um site
     */
    public function index(Site $site)
    {
        $this->authorize('view', $site);

        $media = $site->media()
            ->latest()
            ->paginate(24);

        return view('tenant.media.index', compact('site', 'media'));
    }

    /**
     * Upload de mídia
     */
    public function store(Request $request, Site $site)
    {
        $this->authorize('update', $site);

        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,pdf|max:102400', // 100MB
        ]);

        try {
            $file = $request->file('file');
            $fileSize = $file->getSize();

            // Verificar limite de armazenamento
            $maxStorageMb = $site->subscription?->plan?->max_storage_gb * 1024 ?? 1024;
            $usedStorageMb = $site->current_storage_used_mb;

            if ($usedStorageMb + ($fileSize / 1024 / 1024) > $maxStorageMb) {
                return response()->json([
                    'message' => 'Limite de armazenamento excedido',
                ], 422);
            }

            // Determinar tipo de arquivo
            $mimeType = $file->getMimeType();
            if (str_starts_with($mimeType, 'image')) {
                $type = 'image';
                $fileName = $this->storeImage($file, $site);
            } elseif (str_starts_with($mimeType, 'video')) {
                $type = 'video';
                $fileName = $this->storeVideo($file, $site);
            } else {
                $type = 'document';
                $fileName = $this->storeDocument($file, $site);
            }

            // Extrair meta informações
            $meta = $this->getFileMeta($file, $type);

            // Criar registro de mídia
            $media = $site->media()->create([
                'user_id' => auth()->id(),
                'name' => $file->getClientOriginalName(),
                'file_name' => $fileName,
                'mime_type' => $mimeType,
                'size' => $fileSize,
                'disk' => 'public',
                'path' => "sites/{$site->id}/{$fileName}",
                'url' => Storage::disk('public')->url("sites/{$site->id}/{$fileName}"),
                'type' => $type,
                'meta' => $meta,
            ]);

            // Atualizar armazenamento utilizado
            $site->update([
                'current_storage_used_mb' => $site->current_storage_used_mb + ($fileSize / 1024 / 1024),
            ]);

            return response()->json([
                'message' => 'Arquivo enviado com sucesso!',
                'media' => $media,
            ]);

        } catch (\Exception $e) {
            \Log::error('Media upload error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Erro ao fazer upload: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Deletar mídia
     */
    public function destroy(Site $site, Media $media)
    {
        $this->authorize('update', $site);

        if ($media->site_id !== $site->id) {
            abort(404);
        }

        // Validar se está sendo usada
        if ($media->usage_count > 0) {
            return response()->json([
                'message' => 'Não é possível deletar arquivo em uso. Remova todas as referências primeiro.',
            ], 422);
        }

        // Deletar arquivo
        Storage::disk($media->disk)->delete($media->path);

        // Atualizar armazenamento
        $site->update([
            'current_storage_used_mb' => max(0, $site->current_storage_used_mb - ($media->size / 1024 / 1024)),
        ]);

        $media->delete();

        return response()->json([
            'message' => 'Arquivo deletado com sucesso!',
        ]);
    }

    /**
     * Armazenar imagem com otimização
     */
    private function storeImage($file, $site): string
    {
        $manager = new ImageManager(new GdDriver());
        $image = $manager->read($file->getPathname());

        // Redimensionar se necessário (máximo 1920x1080)
        if ($image->width() > 1920 || $image->height() > 1080) {
            $image->scale(1920, 1080);
        }

        // Otimizar qualidade
        $image->toWebp(quality: 85);

        $fileName = uniqid() . '.webp';
        $path = "sites/{$site->id}/{$fileName}";

        Storage::disk('public')->put($path, (string)$image);

        return $fileName;
    }

    /**
     * Armazenar vídeo
     */
    private function storeVideo($file, $site): string
    {
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = "sites/{$site->id}/{$fileName}";

        $file->storeAs("sites/{$site->id}", $fileName, 'public');

        return $fileName;
    }

    /**
     * Armazenar documento
     */
    private function storeDocument($file, $site): string
    {
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = "sites/{$site->id}/{$fileName}";

        $file->storeAs("sites/{$site->id}", $fileName, 'public');

        return $fileName;
    }

    /**
     * Extrair metadados do arquivo
     */
    private function getFileMeta($file, $type): array
    {
        $meta = [
            'uploaded_at' => now(),
        ];

        if ($type === 'image') {
            try {
                $manager = new ImageManager(new GdDriver());
                $image = $manager->read($file->getPathname());

                $meta['width'] = $image->width();
                $meta['height'] = $image->height();
                $meta['aspect_ratio'] = $image->width() / $image->height();
            } catch (\Exception $e) {
                \Log::warning('Could not extract image meta', ['error' => $e->getMessage()]);
            }
        }

        return $meta;
    }

    /**
     * API: Listar mídia por tipo
     */
    public function getByType(Request $request, Site $site)
    {
        $this->authorize('view', $site);

        $type = $request->query('type', 'image'); // image, video, document

        $media = $site->media()
            ->where('type', $type)
            ->latest()
            ->get();

        return response()->json([
            'media' => $media,
        ]);
    }

    /**
     * API: Buscar mídia
     */
    public function search(Request $request, Site $site)
    {
        $this->authorize('view', $site);

        $query = $request->query('q', '');

        $media = $site->media()
            ->where('name', 'like', "%{$query}%")
            ->latest()
            ->limit(20)
            ->get();

        return response()->json([
            'media' => $media,
        ]);
    }
}