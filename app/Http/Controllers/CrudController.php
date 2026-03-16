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

    public function index(Request $request)
    {
        $paths = explode('/', $request->getRequestUri());
        $entity = $paths[1];

        $view = 'apps.invoice.list';
        if (view()->exists('apps.' . $entity . '.list')) {
            $view = 'apps.' . $entity . '.list';
        }

        return view($view, [
            'config' => config('entities.' . $entity),
        ]);
    }

    public function show($id, Request $request)
    {
        $paths  = explode('/', $request->getRequestUri());
        $entity = $paths[1];

        $config = config('entities.' . $entity);

        return view('apps.invoice.form', [
            'config' => $config,
            'id' => $id,
            'mode' => 'show'
        ]);
    }

    public function edit($id, Request $request)
    {
        $paths  = explode('/', $request->getRequestUri());
        $entity = $paths[1];

        $config = config('entities.' . $entity);

        return view('apps.invoice.form', [
            'config' => $config,
            'id' => $id,
            'mode' => 'edit'
        ]);
    }
    public function create(Request $request)
    {
        $paths  = explode('/', $request->getRequestUri());
        $entity = $paths[1];

        $config = config('entities.' . $entity);

        return view('apps.invoice.form', [
            'config' => $config,
            'mode' => 'create'
        ]);
    }

}
