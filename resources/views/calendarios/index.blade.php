@extends('layouts.app')

@section('title', 'Lista de Calendários')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('content')
<div class="bg-light rounded">
    <div class="card">
        <div class="card-header">
            <div class="page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div>
                            <h5 class="m-0 text-dark">Lista de Calendários</h5>
                            <p class="text-muted">Gerencie os calendários da Escola de Idiomas de Juazeiro-BA</p>
                        </div>
                    </div>
                    <a href="{{ route('Criar-Calendario') }}" class="btn btn-primary btn-sm">Adicionar</a>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ano</th>
                            <th>Ativo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($calendarios as $calendario)
                            <tr class="text-left pointer" data-calendar-id="{{ $calendario->id }}">
                                <td data-label="Ano">{{ $calendario->ano }}</td>
                                <td data-label="Ativo">{{ $calendario->ativo ? 'Ativo' : 'Inativo' }}</td>
                                <td data-label="Ações">
                                    @can('Ver-Calendario')
                                        <a href="{{ route('Ver-Calendario', $calendario->id) }}" class="btn btn-sm btn-info">Visualizar</a>
                                    @endcan
                                    @can('Editar-Calendario')
                                        <a href="{{ route('Editar-Calendario', $calendario->id) }}" class="btn btn-sm btn-primary">Editar</a>
                                        <form action="{{ route('Toggle-Calendario-Active', $calendario->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $calendario->ativo ? 'btn-warning' : 'btn-success' }}">
                                                {{ $calendario->ativo ? 'Desativar' : 'Ativar' }}
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>
@endsection

@push('page_css')
<style>
    .card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .card-header {
        border-left: 0.2rem solid #ff717a !important;
        background-color: #fff;
    }
    .page-title-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .is-invalid {
        border-color: #dc3545 !important;
    }
    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875rem;
    }
    .is-invalid ~ .invalid-feedback {
        display: block;
    }
    .pointer {
        cursor: pointer;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    .btn-sm {
        margin-right: 5px;
    }
    @media (max-width: 768px) {
        .table-responsive {
            border: 0;
        }
        .table thead {
            display: none;
        }
        .table tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
        .table td {
            display: block;
            text-align: right;
            padding-left: 50%;
            position: relative;
            border-bottom: 1px solid #dee2e6;
        }
        .table td::before {
            content: attr(data-label);
            position: absolute;
            left: 1rem;
            width: calc(50% - 1rem);
            padding-right: 1rem;
            text-align: left;
            font-weight: bold;
        }
        .pagination {
            justify-content: center;
        }
    }
</style>
@endpush

@section('javascript')
<script>
    $(document).ready(function() {
        $("#searchButton").click(function() {
            $("#searchSubmit").submit();
        });

        // Impedir que o clique nos botões de ação dispare o redirecionamento da linha
        $('.btn').on('click', function(e) {
            e.stopPropagation();
        });

        // Redirecionar ao clicar na linha, exceto nos botões
        $('tr.pointer').on('click', function(e) {
            if (!$(e.target).closest('.btn').length) {
                const calendarId = $(this).data('calendar-id');
                window.location.href = '{{ route('Ver-Calendario', ':id') }}'.replace(':id', calendarId);
            }
        });

        $('#search').on('keyup', function() {
            let value = $(this).val();
            $.ajax({
                type: 'get',
                url: '{{ route('Search-Calendario') }}',
                data: { 'search': value },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('tbody').html(data.data);
                    $('.pagination').html('');
                    // Reaplicar evento de clique nas novas linhas carregadas via AJAX
                    $('tr.pointer').on('click', function(e) {
                        if (!$(e.target).closest('.btn').length) {
                            const calendarId = $(this).data('calendar-id');
                            window.location.href = '{{ route('Ver-Calendario', ':id') }}'.replace(':id', calendarId);
                        }
                    });
                },
                error: function() {
                    alert('Erro na busca. Tente novamente.');
                }
            });
        });
    });
</script>
@endsection