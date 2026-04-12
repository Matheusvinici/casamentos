@extends('layouts.app')

@section('title', 'Detalhes da Frequência')

@section('content')
    @livewire('frequencias.frequencias-show', ['aulas_id' => $aula->id, 'turma_id' => $aula->turma_id])
@endsection