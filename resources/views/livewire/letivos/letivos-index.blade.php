{{-- Componente: Lista de letivos agrupada por turma com accordion nativo Livewire --}}
<div>
    {{-- Busca --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" wire:model.live.debounce.500ms="search" class="form-control" placeholder="Buscar por nome da turma...">
                <button wire:click="$set('search', '')" class="btn btn-outline-secondary" type="button" {{ $search ? '' : 'disabled' }}>Limpar</button>
            </div>
        </div>
    </div>

    {{-- Mensagens --}}
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Card principal --}}
    <div class="card shadow-xl rounded-1 mx-2 mb-3">
        <div class="card-header text-gray py-3 card-border">
            <h4 class="h5 mb-0 fw-semibold">Lista de dias letivos por Turma e Dia</h4>
        </div>
        <div class="card-body p-4">
            {{-- ← NOVO: Botões para expandir/fechar todas --}}
            <div class="d-flex gap-2 mb-3 justify-content-end">
                <button wire:click="openAll" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-expand-arrows-alt me-1"></i> Expandir Todas
                </button>
                <button wire:click="closeAll" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-compress-arrows-alt me-1"></i> Fechar Todas
                </button>
            </div>

            {{-- Loop por turmas --}}
            @forelse($turmas as $turma)
                <div class="accordion-item-custom mb-3">  {{-- Item por turma --}}
                    {{-- Header da turma (toggle com Livewire) --}}
                    <div class="accordion-header-custom d-flex justify-content-between align-items-center p-3 border rounded-top bg-light cursor-pointer"
                         wire:click="toggleTurma({{ $turma->id }})"
                         style="transition: all 0.3s ease;">
                        <div>
                            <strong>{{ $turma->nome }}</strong>
                            <br>
                            <small class="text-muted">
                                Semestre: {{ $turma->unidade->nome ?? 'N/A' }} |
                                Turno: {{ $turma->turno->abreviacao ?? 'N/A' }}
                            </small>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ $turma->letivos->count() > 0 ? 'bg-primary' : 'bg-warning' }}">
                                {{ $turma->letivos->count() }} aula{{ $turma->letivos->count() !== 1 ? 's' : '' }}
                            </span>
                            <i class="fas fa-chevron-down ms-2"
                               style="transition: transform 0.3s ease; {{ in_array($turma->id, $openTurmas) ? 'transform: rotate(180deg);' : '' }}"></i>
                        </div>
                    </div>

                    {{-- Body da turma (visível se aberta) --}}
                    @if(in_array($turma->id, $openTurmas))
                        <div class="accordion-body-custom p-3 border rounded-bottom bg-white" style="animation: fadeIn 0.3s ease;">
                            {{-- Agrupamento por dia (se houver aulas) --}}
                            @if($turma->letivos->count() > 0)
                                {{-- Ordenar dias logicamente --}}
                                @php
                                    $diasOrdem = [
                                        'segunda-feira' => 1, 'terça-feira' => 2, 'quarta-feira' => 3,
                                        'quinta-feira' => 4, 'sexta-feira' => 5, 'sábado' => 6, 'domingo' => 7
                                    ];
                                    $letivosPorDia = $turma->letivos->groupBy('dia')->sortBy(function ($grupo, $dia) use ($diasOrdem) {
                                        return $diasOrdem[$dia] ?? 8;
                                    });
                                @endphp

                                @foreach($letivosPorDia as $dia => $diaLetivos)
                                    {{-- Header por dia --}}
                                    <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
                                        <h6 class="mb-0">{{ ucfirst($dia) }} ({{ $diaLetivos->count() }} aula{{ $diaLetivos->count() > 1 ? 's' : '' }})</h6>
                                    </div>

                                    {{-- Sub-tabela por dia --}}
                                    <div class="table-responsive mb-3">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="table-light">
                                                <tr class="text-center">
                                                    <th>Aula</th>
                                                    <th>Início</th>
                                                    <th>Término</th>
                                                    <th>Duração</th>
                                                    <th>Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($diaLetivos as $index => $letivo)
                                                    <tr>
                                                        <td data-label="Aula" class="text-center">Aula {{ $index + 1 }}</td>
                                                        <td data-label="Início" class="text-center">{{ $letivo->horario_inicio->format('H:i') }}</td>
                                                        <td data-label="Término" class="text-center">{{ $letivo->horario_saida->format('H:i') }}</td>
                                                        <td data-label="Duração" class="text-center">{{ $letivo->duracaoFormatada }}</td>
                                                        <td data-label="Ações" class="text-center">
                                                            <div class="d-flex gap-1 justify-content-center">
                                                                <a href="{{ route('Ver-Letivo', $letivo->id) }}" class="btn btn-sm btn-outline-primary" title="Visualizar">Ver</a>
                                                                <a href="{{ route('Editar-Letivo', $letivo->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">Editar</a>
                                                                <button wire:click="delete({{ $letivo->id }})" class="btn btn-sm btn-outline-danger" title="Excluir" wire:confirm="Tem certeza que deseja excluir ">Excluir</button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-3 text-muted">
                                    <p>Nenhuma aula cadastrada para esta turma.</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-4">
                    <p>Nenhuma turma encontrada.</p>
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="card-footer bg-light py-3 rounded-bottom-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <small class="text-muted">Total de turmas com aulas: {{ $turmas->total() }}</small>
                {{ $turmas->links() }}
            </div>
        </div>
    </div>

    {{-- CSS: Transições nativas para accordion --}}
    <style>
        .card { transition: all 0.3s ease; }
        .card-border { border-left: 0.2rem solid #ff717a !important; height: 55px; }
        .accordion-item-custom { border: 1px solid #dee2e6; border-radius: 0.375rem; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); overflow: hidden; }
        .accordion-header-custom { background-color: #f8f9fa; cursor: pointer; font-weight: 600; border: none; width: 100%; text-align: left; padding: 1rem; }
        .accordion-header-custom:hover { background-color: #e9ecef; }
        .accordion-body-custom { background-color: #ffffff; border-top: 1px solid #dee2e6; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .accordion-body-custom { animation: fadeIn 0.3s ease; }
        .table-sm th, .table-sm td { padding: 0.5rem; font-size: 0.875rem; }
        @media (max-width: 768px) {
            .accordion-header-custom { padding: 1rem; font-size: 1rem; }
            .d-flex.gap-2 { flex-direction: column; align-items: stretch; }
            .table-responsive { border: 0; margin-bottom: 1rem; }
            .table thead { display: none; }
            .table tr { display: block; margin-bottom: 0.5rem; border: 1px solid #dee2e6; border-radius: 0.25rem; background: white; }
            .table td { display: block; text-align: right; padding-left: 50%; position: relative; border-bottom: 1px solid #dee2e6; }
            .table td::before { content: attr(data-label); position: absolute; left: 1rem; width: calc(50% - 1rem); padding-right: 1rem; text-align: left; font-weight: bold; }
            .table td[data-label="Ações"] .d-flex { justify-content: flex-end; gap: 0.25rem; }
            .d-flex.justify-content-between { flex-direction: column; gap: 0.5rem; align-items: stretch; }
            .pagination { justify-content: center; }
        }
    </style>
</div>
