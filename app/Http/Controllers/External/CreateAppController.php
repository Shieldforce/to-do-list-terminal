<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateAppController extends Controller
{
    public function createApp(Request $request)
    {
        $pathFile = base_path() . "/resources/shells/create_app.sh";
        if(file_exists($pathFile)) {
           return response(File::get($pathFile))
               ->header('Content-Type', 'text/plain');
        }

        throw new NotFoundHttpException("File create app not found!");
    }
}
