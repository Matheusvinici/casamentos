@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <h5 class="m-0 text-dark">Detalhes do Aluno</h5>
                        <p class="text-muted">Visualize as informações do aluno</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid py-3 py-md-4">
        <div class="card shadow-xl rounded-1 mx-2 mb-3">
            <div class="card-header text-gray py-3 card-border">
                <h4 class="h4 mb-0 fw-semibold">Informações do Aluno</h4>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <!-- Left Column: Informações da Escola e Informações da Escola de Idiomas -->
                    <div class="col-md-6">
                        <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">Informações da Escola</h5>
                        <div class="form-group mb-2">
                            <label class="fw-semibold">Tipo</label>
                            <p>{{ ucfirst(str_replace('_', ' ', $aluno->tipo)) }}</p>
                        </div>
                        @if($aluno->tipo === 'aluno_rede')
                        <div class="form-group mb-2">
                            <label class="fw-semibold">Escola</label>
                            <p>{{ $aluno->escola ? $aluno->escola->nome : 'N/A' }}</p>
                        </div>
                        <div class="form-group mb-2">
                            <label class="fw-semibold">Turno de Estudo na Escola de Origem</label>
                            <p>{{ $aluno->turno_escola ?: 'N/A' }}</p>
                        </div>
                        <div class="form-group mb-2">
                            <label class="fw-semibold">Origem</label>
                            <p>{{ ucfirst($aluno->origem) ?: 'N/A' }}</p>
                        </div>
                        <div class="form-group mb-2">
                            <label class="fw-semibold">Responsável</label>
                            <p>{{ $aluno->responsavel_nome ?: 'N/A' }}</p>
                        </div>
                        @elseif($aluno->tipo === 'aluno_estado')
                        <div class="form-group mb-2">
                            <label class="fw-semibold">Escola Estadual</label>
                            <p>{{ $aluno->escola_estado ?: 'N/A' }}</p>
                        </div>
                        <div class="form-group mb-2">
                            <label class="fw-semibold">Turno de Estudo na Escola de Origem</label>
                            <p>{{ $aluno->turno_escola ?: 'N/A' }}</p>
                        </div>
                        <div class="form-group mb-2">
                            <label class="fw-semibold">Origem</label>
                            <p>{{ ucfirst($aluno->origem) ?: 'N/A' }}</p>
                        </div>
                        <div class="form-group mb-2">
                            <label class="fw-semibold">Responsável</label>
                            <p>{{ $aluno->responsavel_nome ?: 'N/A' }}</p>
                        </div>
                        @endif
                        @if($aluno->tipo === 'servidor' || $aluno->tipo === 'outros')
                        <div class="form-group mb-2">
                            <label class="fw-semibold">Secretaria de Origem</label>
                            <p>{{ $aluno->origem_servidor ?: 'N/A' }}</p>
                        </div>
                        @endif

                        <h5 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-3">Informações da Escola de Idiomas</h5>
                        <div class="form-group mb-2">
                            <label class="fw-semibold">Turno de Estudo na Escola de Idiomas</label>
                            <p>{{ $aluno->turno_idioma ?: 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Right Column: Informações Pessoais e Informações de Endereço -->
                   <div class="col-md-6">
    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">Informações Pessoais</h5>
    <div class="form-group mb-2">
        <label class="fw-semibold">Nome</label>
        <p>{{ $aluno->nome }}</p>
    </div>
    <div class="form-group mb-2">
        <label class="fw-semibold">Data de Nascimento</label>
        <p>{{ $aluno->data_nascimento ? date('d/m/Y', strtotime($aluno->data_nascimento)) : 'N/A' }}</p>
    </div>
    <div class="form-group mb-2">
        <label class="fw-semibold">Telefone</label>
        <p>{{ $aluno->telefone ?: 'N/A' }}</p>
    </div>
    <div class="form-group mb-2">
        <label class="fw-semibold">CPF do Aluno(a)</label>
        <p>{{ $aluno->aluno_cpf ?: 'N/A' }}</p>
    </div>
    <div class="form-group mb-2">
        <label class="fw-semibold">Ano do Aluno(a)</label>
        <p>{{ $aluno->ano_escolar ?: 'N/A' }}</p>
    </div>
    
    <!-- Campo Raça/Cor -->
    <div class="form-group mb-2">
        <label class="fw-semibold">Raça/Cor</label>
        <p>{{ $aluno->raca_cor ?: 'Não informado' }}</p>
    </div>
    
    <!-- Campo Deficiência -->
    <div class="form-group mb-2">
        <label class="fw-semibold">Deficiência</label>
        @if($aluno->deficiencias && $aluno->deficiencias->count() > 0)
            <div>
                @foreach($aluno->deficiencias as $deficiencia)
                    <span class="badge bg-info me-1 mb-1" style="font-size: 0.9rem; padding: 5px 10px;">
                        {{ $deficiencia->nome }}
                    </span>
                @endforeach
            </div>
        @else
            <p>Não possui deficiência</p>
        @endif
    </div>
    
    <div class="form-group mb-2">
        <label class="fw-semibold">Email</label>
        <p>{{ $aluno->email ?: 'N/A' }}</p>
    </div>
    <div class="form-group mb-2">
        <label class="fw-semibold">Contato de Emergência</label>
        <p>{{ $aluno->contato_emergencia ?: 'N/A' }}</p>
    </div>

    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-3">Informações de Endereço</h5>
    <div class="form-group mb-2">
        <label class="fw-semibold">Endereço</label>
        <p>{{ $aluno->endereco ?: 'N/A' }}</p>
    </div>
    <div class="form-group mb-2">
        <label class="fw-semibold">País</label>
        <p>{{ $aluno->pais_id ? ($aluno->pais->nome ?? 'N/A') : 'N/A' }}</p>
    </div>
    <div class="form-group mb-2">
        <label class="fw-semibold">Cidade</label>
        <p>{{ $aluno->cidade_id ? ($aluno->cidade->nome ?? 'N/A') : 'N/A' }}</p>
    </div>
    <div class="form-group mb-2">
        <label class="fw-semibold">Bairro</label>
        <p>{{ $aluno->bairro_id ? ($aluno->bairro->nome ?? 'N/A') : 'N/A' }}</p>
    </div>
    <div class="form-group mb-2">
        <label class="fw-semibold">Distrito</label>
        <p>{{ $aluno->distrito ?: 'N/A' }}</p>
    </div>
</div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('Editar-Aluno', $aluno->id) }}" class="btn btn-primary">Editar</a>
                    <a href="{{ route('Listar-Alunos') }}" class="btn btn-outline-secondary">Voltar</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page_css')
<style>
    .card {
        transition: all 0.3s ease;
    }
    .card-border {
        border-left: 0.2rem solid #ff7176 !important;
        height: 55px;
    }
    .form-group {
        margin-bottom: 0.5rem;
    }
    .border-bottom {
        border-bottom: 2px solid #dee2e6 !important;
    }
    @media (max-width: 768px) {
        .form-group {
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush
@endsection