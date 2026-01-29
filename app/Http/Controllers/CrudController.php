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

        return view('apps.invoice.list', [
            'config' => config('entities.' . $entity),
        ]);
    }

    public function show($id, Request $request)
    {
        $paths  = explode('/', $request->getRequestUri());
        $entity = $paths[1];

        $config = config('entities.' . $entity);

        return view('apps.invoice.show', [
            'config' => $config,
        ]);
    }

}
