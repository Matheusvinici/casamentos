@extends('layouts.app')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('content')
    <div class="bg-light rounded">
        <div class="card">
            <div class="card-header">
                <div class="page-title">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <h5 class="m-0">
                                @if ($show)
                                    Visualizar Calendário
                                @else
                                    {{ $edit ? 'Editar' : 'Adicionar' }} Calendário
                                @endif
                            </h5>
                        </div>
                        <div>
                            <a href="{{ route('Listar-Calendarios') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
                            @if ($show && isset($calendario->id))
                                <a href="{{ route('Editar-Calendario', $calendario->id) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            @endif
                            @can('Deletar-Calendario')
                                @if (($edit || $show) && isset($calendario->id))
                                    <button onclick="excluiregistro('{{ $calendario->id }}')" class="btn btn-sm btn-outline-danger" title="Excluir Calendário">Excluir</button>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="calendarioForm"
                    action="{{ $edit && isset($calendario->id) ? route('Atualizar-Calendario', $calendario->id) : route('Gravar-Calendario') }}"
                    method="POST">
                    @csrf
                    @if ($edit && isset($calendario->id))
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="ano" class="form-label">Ano</label>
                            <input {{ $show ? 'disabled' : '' }} value="{{ old('ano', $calendario->ano ?? '') }}"
                                type="number" class="form-control" id="ano" name="ano" placeholder="Ano" required>
                        </div>

                        <div class="col-md-2">
                            <label for="semestre" class="form-label">Semestre</label>
                            <select {{ $show ? 'disabled' : '' }} class="form-control" id="semestre" name="semestre" required>
                                <option value="1" {{ old('semestre', $calendario->semestre ?? '') == '1' ? 'selected' : '' }}>1º Semestre</option>
                                <option value="2" {{ old('semestre', $calendario->semestre ?? '') == '2' ? 'selected' : '' }}>2º Semestre</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="inicio" class="form-label">Data de Início</label>
                            <input {{ $show ? 'disabled' : '' }}
                                value="{{ old('inicio', $calendario->inicio ? $calendario->inicio->format('Y-m-d') : '') }}"
                                type="date" class="form-control" id="inicio" name="inicio" required>
                        </div>

                        <div class="col-md-2">
                            <label for="fim" class="form-label">Data de Fim</label>
                            <input {{ $show ? 'disabled' : '' }}
                                value="{{ old('fim', $calendario->fim ? $calendario->fim->format('Y-m-d') : '') }}"
                                type="date" class="form-control" id="fim" name="fim" required>
                        </div>

                        <div class="col-md-2">
                            <label for="total_dias_letivos" class="form-label">Dias Letivos</label>
                            <input {{ $show ? 'disabled' : '' }}
                                value="{{ old('total_dias_letivos', $calendario->total_dias_letivos ?? '') }}"
                                type="number" class="form-control" id="total_dias_letivos" name="total_dias_letivos"
                                required>
                        </div>

                        <div class="col-md-2">
                            <label for="ativo" class="form-label">Ativo</label>
                            <select {{ $show ? 'disabled' : '' }} class="form-control" id="ativo" name="ativo"
                                required>
                                <option value="1" {{ old('ativo', $calendario->ativo ?? 0) ? 'selected' : '' }}>
                                    Ativo
                                </option>
                                <option value="0" {{ old('ativo', $calendario->ativo ?? 0) == 0 ? 'selected' : '' }}>
                                    Inativo
                                </option>
                            </select>
                        </div>

                        @if (!$show)
                            <div class="col-md-12 mt-3">
                                <button id="calendarioSubmit" type="submit" class="btn btn-primary btn-sm">
                                    {{ $edit ? 'Atualizar' : 'Adicionar' }}
                                </button>
                            </div>
                        @endif
                    </div>
                </form>

                @if ($show || $edit)
                    <hr class="my-4" />
                    <h5 class="text-center mb-4">Bimestres/Unidades</h5>
                    <form id="unidadeForm"
                        action="{{ $edit && isset($unidade) ? route('Atualizar-Unidade', $unidade->id ?? '') : route('Gravar-Unidade') }}"
                        method="POST">
                        @csrf
                        @if ($edit && isset($unidade))
                            @method('PUT')
                        @endif
                        <div id="unidades" {{ $show ? 'style=display:none' : '' }} class="row g-3">
                            <input type="hidden" name="calendario_id" value="{{ $calendario->id ?? '' }}">

                            <div class="col-md-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control uppercase" id="nome" name="nome"
                                    value="{{ old('nome') }}" placeholder="Ex.: 1º Bimestre" required>
                            </div>

                            <div class="col-md-1">
                                <label for="sigla" class="form-label">Sigla</label>
                                <input type="text" class="form-control uppercase" id="sigla" name="sigla"
                                    value="{{ old('sigla') }}" placeholder="1B" required>
                            </div>

                            <div class="col-md-2">
                                <label for="data_inicio" class="form-label">Data Início</label>
                                <input type="date" class="form-control" id="data_inicio" name="data_inicio"
                                    value="{{ old('data_inicio') }}" required>
                            </div>

                            <div class="col-md-2">
                                <label for="data_final" class="form-label">Data Final</label>
                                <input type="date" class="form-control" id="data_final" name="data_final"
                                    value="{{ old('data_final') }}" required>
                            </div>

                            <div class="col-md-2">
                                <label for="data_limite_lancamento" class="form-label">Limite Lançamentos</label>
                                <input type="date" class="form-control" id="data_limite_lancamento"
                                    name="data_limite_lancamento" value="{{ old('data_limite_lancamento') }}">
                            </div>

                            <div class="col-md-1">
                                <label for="qtd_dias_letivos" class="form-label">Qtd Dias</label>
                                <input type="number" class="form-control" id="qtd_dias_letivos"
                                    name="qtd_dias_letivos" value="{{ old('qtd_dias_letivos') }}"
                                    placeholder="Qtd dias" required>
                            </div>

                            <div class="col-md-1">
                                <label for="ativo" class="form-label">Ativo</label>
                                <select name="ativo" id="ativo" class="form-control" required>
                                    <option value="1" {{ old('ativo', '1') == '1' ? 'selected' : '' }}>
                                        Ativo
                                    </option>
                                    <option value="0" {{ old('ativo', '') == '0' ? 'selected' : '' }}>
                                        Inativo
                                    </option>
                                </select>
                            </div>

                            @if ($edit)
                                <div class="col-md-12 mt-3">
                                    <div id="add">
                                        <button type="submit" class="btn btn-primary btn-sm">Adicionar</button>
                                    </div>
                                    <div id="update" class="d-none">
                                        <button id="unidadeSubmit" type="submit" class="btn btn-warning btn-sm">
                                            Atualizar
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                @endif

                @if ($show || $edit)
                    <hr class="my-4" />
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        @if ($edit)
                                            <th style="text-align: left; white-space:nowrap;">Ações</th>
                                        @endif
                                        <th>Nome</th>
                                        <th>Sigla</th>
                                        <th>Data Início</th>
                                        <th>Data Final</th>
                                        <th>Limite Lançamento</th>
                                        <th>Qtd Dias Letivos</th>
                                        <th>Ativo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($unidades->isNotEmpty())
                                        @foreach ($unidades as $unidade)
                                            <tr class="text-left">
                                                @if ($edit)
                                                    <td style="text-align: left; white-space:nowrap;">
                                                        <button onclick='editUnidade(
                                                            "{{ $unidade->id }}",
                                                            "{{ $unidade->nome }}",
                                                            "{{ $unidade->sigla }}",
                                                            "{{ $unidade->data_inicio->format('Y-m-d') }}",
                                                            "{{ $unidade->data_final->format('Y-m-d') }}",
                                                            "{{ $unidade->data_limite_lancamento ? $unidade->data_limite_lancamento->format('Y-m-d') : '' }}",
                                                            "{{ $unidade->qtd_dias_letivos }}",
                                                            "{{ $unidade->ativo ? '1' : '0' }}"
                                                            )'
                                                            class="btn btn-sm btn-primary">Editar</button>
                                                        <form action="{{ route('Deletar-Unidade', $unidade->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Você tem certeza que deseja excluir esta unidade?')">Excluir</button>
                                                        </form>
                                                    </td>
                                                @endif
                                                <td>{{ $unidade->nome }}</td>
                                                <td>{{ $unidade->sigla }}</td>
                                                <td>{{ $unidade->data_inicio->format('d/m/Y') }}</td>
                                                <td>{{ $unidade->data_final->format('d/m/Y') }}</td>
                                                <td>{{ $unidade->data_limite_lancamento ? $unidade->data_limite_lancamento->format('d/m/Y') : 'N/A' }}</td>
                                                <td>{{ $unidade->qtd_dias_letivos }}</td>
                                                <td>{{ $unidade->ativo ? 'Criada' : 'Inativa' }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="{{ $edit ? 8 : 7 }}">Nenhuma unidade cadastrada.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
    <script>
        $(document).ready(function() {
            $('.uppercase').keyup(function() {
                $(this).val($(this).val().toUpperCase());
            });
        });

        function editUnidade(id, nome, sigla, data_inicio, data_final, data_limite_lancamento, qtd_dias_letivos, ativo) {
            $("input#nome").val(nome);
            $("input#sigla").val(sigla);
            $("input#data_inicio").val(data_inicio);
            $("input#data_final").val(data_final);
            $("input#data_limite_lancamento").val(data_limite_lancamento);
            $("#qtd_dias_letivos").val(qtd_dias_letivos);
            $("select#ativo").val(ativo);
            $('#unidadeForm').attr('action', "{{ route('Atualizar-Unidade', ['id' => ':id']) }}".replace(':id', id));
            $('#unidadeForm').find('input[name="_method"]').remove();
            $("<input>").attr({ type: 'hidden', name: '_method', value: 'PUT' }).appendTo('#unidadeForm');
            $("#add").hide();
            $("#update").show();
        }

        function excluiregistro(id) {
            if (confirm('Você tem certeza que deseja excluir este calendário?')) {
                $.ajax({
                    url: '{{ route('Deletar-Calendario', ':id') }}'.replace(':id', id),
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        window.location.href = '{{ route('Listar-Calendarios') }}';
                    },
                    error: function() {
                        alert('Erro ao excluir calendário. Tente novamente.');
                    }
                });
            }
        }

        $(document).on('keydown', ':input:not(textarea)', function(event) {
            return event.key !== 'Enter';
        });
    </script>
@stop