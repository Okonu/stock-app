<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResumeController extends Controller
{
    public function show($filename)
    {
        $path = storage_path('/app/public/' . $filename);

        if (file_exists($path)) {
            return response()->file($path);
        }

        abort(404);
    }
}
