<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;

class CrudController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private function getConfig($module, $chapter = null)
    {
        if ($chapter) {
            $configPath = config_path("entities/{$module}/{$chapter}.php");
            if (!file_exists($configPath)) {
                return null;
            }
            return include $configPath;
        }

        return config('entities.' . $module);
    }

    public function index(Request $request, $module = null, $chapter = null)
    {
        if (!$module) {
            $paths = explode('/', $request->getRequestUri());
            $module = $paths[1];
        }

        $config = $this->getConfig($module, $chapter);

        if (!$config) {
            return response()->view('pages.error404', [], 404);
        }

        $view = 'apps.invoice.list';
        $entityName = $chapter ?: $module;
        if (view()->exists('apps.' . $entityName . '.list')) {
            $view = 'apps.' . $entityName . '.list';
        }

        return view($view, [
            'config' => $config,
        ]);
    }

    public function show($id, Request $request, $module = null, $chapter = null)
    {
        if (!$module) {
            $paths = explode('/', $request->getRequestUri());
            $module = $paths[1];
        }

        $config = $this->getConfig($module, $chapter);

        if (!$config) {
            return response()->view('pages.error404', [], 404);
        }

        return view('apps.invoice.form', [
            'config' => $config,
            'id' => $id,
            'mode' => 'show'
        ]);
    }

    public function edit($id, Request $request, $module = null, $chapter = null)
    {
        if (!$module) {
            $paths = explode('/', $request->getRequestUri());
            $module = $paths[1];
        }

        $config = $this->getConfig($module, $chapter);

        if (!$config) {
            return response()->view('pages.error404', [], 404);
        }

        return view('apps.invoice.form', [
            'config' => $config,
            'id' => $id,
            'mode' => 'edit'
        ]);
    }

    public function create(Request $request, $module = null, $chapter = null)
    {
        if (!$module) {
            $paths = explode('/', $request->getRequestUri());
            $module = $paths[1];
        }

        $config = $this->getConfig($module, $chapter);

        if (!$config) {
            return response()->view('pages.error404', [], 404);
        }

        return view('apps.invoice.form', [
            'config' => $config,
            'mode' => 'create'
        ]);
    }

}
