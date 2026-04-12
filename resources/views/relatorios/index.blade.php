@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-5 text-center fw-bold text-primary">Relatórios</h1>
    
    <div class="row g-4">
        @php
            $reports = [
                ['route' => 'Relatorios-Matriculas-Por-Turma', 'name' => 'Matrículas por Turma', 'icon' => 'bi-people-fill'],
                ['route' => 'Relatorios-Matriculas-Por-Curso', 'name' => 'Matrículas por Curso', 'icon' => 'bi-book-fill'],
                ['route' => 'Relatorios-Matriculas-Por-Nivel', 'name' => 'Matrículas por Nível', 'icon' => 'bi-ladder'],
                ['route' => 'Relatorios-Todas-Turmas', 'name' => 'Todas as Turmas', 'icon' => 'bi-collection-fill'],
                ['route' => 'Relatorios-Localidades-Alunos', 'name' => 'Localidades dos Alunos', 'icon' => 'bi-geo-alt-fill'],
                ['route' => 'Relatorios-Faixa-Etaria-Curso', 'name' => 'Faixa Etária por Curso', 'icon' => 'bi-bar-chart-fill'],
                ['route' => 'Relatorios-Turmas-Professores', 'name' => 'Turmas dos Professores', 'icon' => 'bi-person-workspace'],
                ['route' => 'Relatorios-Professores-Ativos', 'name' => 'Professores Ativos', 'icon' => 'bi-person-check-fill'],
                ['route' => 'Relatorios-Quantidade-Matriculas-Ativas', 'name' => 'Quantidade de Matrículas Ativas', 'icon' => 'bi-graph-up'],
            ];
        @endphp
        
        @foreach($reports as $report)
            <div class="col-md-4">
                <a href="{{ route($report['route']) }}" class="text-decoration-none">
                    <div class="card shadow-sm h-100 border-0 hover-shadow">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi {{ $report['icon'] }} me-3 fs-3 text-primary"></i>
                            <h5 class="card-title mb-0">{{ $report['name'] }}</h5>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
    }
</style>

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endsection
@endsection