<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index() {
        return view('index', [
            'title' => 'Gestão do PROAP',
        ]);
    }
}
