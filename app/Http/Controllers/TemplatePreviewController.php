<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class TemplatePreviewController extends Controller
{
    public function show($slug)
    {
        $path = resource_path("templates/$slug/index.html");

        if (!File::exists($path)) {
            abort(404, "Template não encontrado");
        }

        $html = File::get($path);

        return response($html)->header('Content-Type', 'text/html');
    }
}