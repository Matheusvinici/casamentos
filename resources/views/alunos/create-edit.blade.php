@extends('layouts.app')

@section('title', isset($aluno) ? 'Editar Aluno' : 'Criar Novo Aluno')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <h5 class="m-0 text-dark">{{ isset($aluno) ? 'Editar Aluno' : 'Criar Novo Aluno' }}</h5>
                        <p class="text-muted">Gerencie os dados do aluno no sistema</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid py-3 py-md-4">
        <div class="card shadow-xl rounded-1 mx-2 mb-3">
            <div class="card-header text-gray py-3 card-border">
                <h4 class="h5 mb-0 fw-semibold">Formulário de Cadastro</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ isset($aluno) ? route('alunos.update', $aluno->id) : route('alunos.store') }}" method="POST">
                    @csrf
                    @if(isset($aluno))
                        @method('PUT')
                    @endif
                    <div class="row">
                        <!-- Informações Pessoais -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Informações Pessoais</h6>
                            <div class="form-group mb-2">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" 
                                       placeholder="Digite o nome completo" 
                                       value="{{ old('nome', isset($aluno) ? $aluno->nome : '') }}" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="data_nascimento">Data de Nascimento</label>
                                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" 
                                       placeholder="dd/mm/aaaa" 
                                       value="{{ old('data_nascimento', isset($aluno) ? $aluno->data_nascimento : '') }}" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="telefone">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" 
                                       placeholder="Ex: (12) 3456-7890" 
                                       value="{{ old('telefone', isset($aluno) ? $aluno->telefone : '') }}">
                            </div>
                            <div class="form-group mb-2">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="Ex: aluno@exemplo.com" 
                                       value="{{ old('email', isset($aluno) ? $aluno->email : '') }}">
                            </div>
                            <div class="form-group mb-2">
                                <label for="contato_emergencia">Contato de Emergência</label>
                                <input type="text" class="form-control" id="contato_emergencia" name="contato_emergencia" 
                                       placeholder="Ex: (12) 3456-7890" 
                                       value="{{ old('contato_emergencia', isset($aluno) ? $aluno->contato_emergencia : '') }}">
                            </div>
                        </div>
                        <!-- Informações de Endereço, Escola, Idiomas e Afins -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Informações de Endereço</h6>
                            <div class="form-group mb-2">
                                <label for="endereco">Endereço</label>
                                <input type="text" class="form-control" id="endereco" name="endereco" 
                                       placeholder="Ex: Rua das Flores, 123" 
                                       value="{{ old('endereco', isset($aluno) ? $aluno->endereco : '') }}">
                            </div>
                            <div class="form-group mb-2">
                                <label for="bairro_id">País</label>
                                <select class="form-control" id="pais_id" name="pais_id">
                                    <option value="">Selecione um país</option>
                                    @foreach($paises as $pais)
                                        <option value="{{ $pais->id }}" 
                                                {{ old('pais_id', isset($aluno) ? $aluno->pais_id : '') == $pais->id ? 'selected' : '' }}>
                                            {{ $pais->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label for="cidade_id">Cidade</label>
                                <select class="form-control" id="cidade_id" name="cidade_id">
                                    <option value="">Selecione uma cidade</option>
                                    @foreach($cidades as $cidade)
                                        <option value="{{ $cidade->id }}" 
                                                {{ old('cidade_id', isset($aluno) ? $aluno->cidade_id : '') == $cidade->id ? 'selected' : '' }}>
                                            {{ $cidade->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label for="bairro_id">Bairro</label>
                                <select class="form-control" id="bairro_id" name="bairro_id">
                                    <option value="">Selecione um bairro</option>
                                    @foreach($bairros as $bairro)
                                        <option value="{{ $bairro->id }}" 
                                                {{ old('bairro_id', isset($aluno) ? $aluno->bairro_id : '') == $bairro->id ? 'selected' : '' }}>
                                            {{ $bairro->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label for="distrito_id">Distrito</label>
                                <select class="form-control" id="distrito_id" name="distrito_id">
                                    <option value="">Selecione um distrito</option>
                                    @foreach($distritos as $distrito)
                                        <option value="{{ $distrito->id }}" 
                                                {{ old('distrito_id', isset($aluno) ? $aluno->distrito_id : '') == $distrito->id ? 'selected' : '' }}>
                                            {{ $distrito->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-3">Escola</h6>
                            <div class="form-group mb-2">
                                <label for="escola_id">Escola</label>
                                <select class="form-control" id="escola_id" name="escola_id">
                                    <option value="">Selecione uma escola</option>
                                    @foreach($escolas as $escola)
                                        <option value="{{ $escola->id }}" 
                                                {{ old('escola_id', isset($aluno) ? $aluno->escola_id : '') == $escola->id ? 'selected' : '' }}>
                                            {{ $escola->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label for="turno_escola">Turno de Estudo na Escola de Origem</label>
                                <input type="text" class="form-control" id="turno_escola" name="turno_escola" 
                                       placeholder="Ex: Matutino, Vespertino" 
                                       value="{{ old('turno_escola', isset($aluno) ? $aluno->turno_escola : '') }}">
                            </div>
                            <div class="form-group mb-2">
                                <label for="origem">Origem</label>
                                <select class="form-control" id="origem" name="origem" required>
                                    <option value="">Selecione a origem</option>
                                    <option value="municipal" {{ old('origem', isset($aluno) ? $aluno->origem : '') == 'municipal' ? 'selected' : '' }}>Municipal</option>
                                    <option value="estadual" {{ old('origem', isset($aluno) ? $aluno->origem : '') == 'estadual' ? 'selected' : '' }}>Estadual</option>
                                </select>
                            </div>
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-3">Idiomas e Afins</h6>
                            <div class="form-group mb-2">
                                <label for="turno_idioma">Turno de Estudo na Escola de Idioma</label>
                                <input type="text" class="form-control" id="turno_idioma" name="turno_idioma" 
                                       placeholder="Ex: Matutino, Vespertino" 
                                       value="{{ old('turno_idioma', isset($aluno) ? $aluno->turno_idioma : '') }}">
                            </div>
                            <div class="form-group mb-2">
                                <label for="tipo">Tipo</label>
                                <select class="form-control" id="tipo" name="tipo" required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="aluno_rede" {{ old('tipo', isset($aluno) ? $aluno->tipo : '') == 'aluno_rede' ? 'selected' : '' }}>Aluno Rede</option>
                                    <option value="servidor" {{ old('tipo', isset($aluno) ? $aluno->tipo : '') == 'servidor' ? 'selected' : '' }}>Servidor</option>
                                    <option value="outros" {{ old('tipo', isset($aluno) ? $aluno->tipo : '') == 'outros' ? 'selected' : '' }}>Outros</option>

                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label for="responsavel_id">Responsável</label>
                                <select class="form-control" id="responsavel_id" name="responsavel_id">
                                    <option value="">Selecione um responsável</option>
                                    @foreach($responsaveis as $responsavel)
                                        <option value="{{ $responsavel->id }}" 
                                                {{ old('responsavel_id', isset($aluno) ? $aluno->responsavel_id : '') == $responsavel->id ? 'selected' : '' }}>
                                            {{ $responsavel->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <a href="{{ route('Listar-Alunos') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
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
        border-left: 0.2rem solid #ff717a !important;
        height: 55px;
    }
    .form-group {
        margin-bottom: 0.5rem; /* Reduced margin for tighter layout */
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