{{-- Componente: Visualiza letivo --}}
<div>
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-xl rounded-1 mx-2 mb-3">
        <div class="card-header text-gray py-3 card-border">
            <h4 class="h5 mb-0 fw-semibold">Detalhes do Letivo</h4>
        </div>
        <div class="card-body p-4">
            {{-- Info geral: Turma, Semestre e Turno --}}
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Turma:</label>
                    <p>{{ $letivo->turma->nome ?? 'Sem turma' }} ({{ $letivo->turma->curso->abreviacao ?? 'N/A' }} - {{ $letivo->turma->nivel->abreviacao ?? 'N/A' }})</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Semestre:</label>  {{-- Novo --}}
                    <p>{{ $letivo->turma->unidade->nome ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Turno:</label>
                    <p>{{ $letivo->turma->turno->abreviacao ?? 'N/A' }}</p>
                </div>
            </div>
            {{-- Tabela: Uma linha por letivo (sem loop) --}}
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Aula</th>
                            <th>Dia</th>
                            <th>Início</th>
                            <th>Término</th>
                            <th>Duração</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Aula 1</td>
                            <td>{{ ucfirst($letivo->dia) }}</td>
                            <td>{{ $letivo->horario_inicio->format('H:i') }}</td>
                            <td>{{ $letivo->horario_saida->format('H:i') }}</td>
                            <td>{{ $letivo->duracaoFormatada }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('Editar-Letivo', $letivo->id) }}" class="btn btn-primary">Editar</a>
                <a href="{{ route('Listar-Letivos') }}" class="btn btn-outline-secondary">Voltar</a>
            </div>
        </div>
    </div>

    <style>
        .card { transition: all 0.3s ease; }
        .card-border { border-left: 0.2rem solid #ff717a !important; height: 55px; }
        .alert-success, .alert-danger { font-size: 0.875rem; }
        .form-label { font-size: 0.9rem; color: #333; }
        p { margin: 0; font-size: 1rem; color: #555; }
    </style>
</div>
