@extends('layouts.app')

@section('title', '{{ $editingFrequenciaId ? "Editar Frequência" : "Registrar Nova Frequência" }}')

@section('content')
    @livewire('frequencias.frequencias-create', ['turma_id' => $turma_id, 'frequencia' => $frequencia ?? null])
@endsection
