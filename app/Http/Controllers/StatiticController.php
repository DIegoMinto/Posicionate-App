<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatiticController extends Controller
{
    public function index()
    {
        $usuario = auth()->user()->load('persona');
        return view('statitics.index', compact('usuario'));
    }
}