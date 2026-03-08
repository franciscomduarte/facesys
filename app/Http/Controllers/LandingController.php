<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing.index');
    }

    public function planos()
    {
        return view('landing.planos');
    }

    public function contato()
    {
        return view('landing.contato');
    }

    public function demo()
    {
        return view('landing.demo');
    }

    public function termos()
    {
        return view('landing.termos');
    }

    public function privacidade()
    {
        return view('landing.privacidade');
    }
}
