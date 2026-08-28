<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PerfilController extends Controller
{
    public function index(): View
    {
        return view('perfil.index');
    }
}