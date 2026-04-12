<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConteudoMinistradoController extends Controller
{
    public function index()
    {
        if (Auth::guard('professor')->check()) {
            return view('conteudos.index-professor');
        }
        return view('conteudos.index');
    }

    public function create(Request $request)
    {
        $turma_id = $request->query('turma_id');
        return view('conteudos.create-edit-show', [
            'turma_id' => $turma_id,
            'aulas_id' => null,
        ]);
    }

    public function edit($turma_id, $aulas_id)
    {
        return view('conteudos.create-edit-show', [
            'turma_id' => $turma_id,
            'aulas_id' => $aulas_id,
        ]);
    }

    public function show($turma_id, $aulas_id)
    {
        return view('conteudos.create-edit-show', [
            'turma_id' => $turma_id,
            'aulas_id' => $aulas_id,
        ]);
    }
}