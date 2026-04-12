@extends('layouts.app')

@section('title', 'Conteúdo Ministrado')

@section('content')
    @livewire('conteudos.conteudos-manager', [
        'turma_id' => $turma_id ?? null,
        'aulas_id' => $aulas_id ?? null
    ])
@endsection