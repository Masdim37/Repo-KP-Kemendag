<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class torrabController extends Controller
{
    public function ShowUploadTorRab()
    {
        return view('menu.upload-dokumen.torrab');
    }
}
