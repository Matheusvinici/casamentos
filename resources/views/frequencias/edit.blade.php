@extends('layouts.app')

@section('title', '{{ isset($frequencia) ? "Editar Frequência" : "Registrar Nova Frequência" }}')

@section('content')
    @livewire('frequencias.frequencias-edit', ['turma_id' => $turma_id ?? null, 'data' => $data ?? null])
@endsection
