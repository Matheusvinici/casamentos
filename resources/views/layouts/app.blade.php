<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SEDUC</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    @livewireStyles
    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <style>
        .sidebar-custom {
            background-color: #f9fafb !important;
            border-right: 1px solid #e5e7eb;
        }

        .brand-area {
            background-color: #ffffff;
            padding: 15px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .brand-logo {
            display: block;
            margin: 0 auto;
            height: 45px;
            width: auto;
        }

        .brand-text {
            font-size: 1.3rem;
            font-weight: 700;
            color: #374151 !important;
            text-align: center;
            margin-top: 5px;
        }

        .nav-sidebar .nav-link {
            color: #374151 !important;
            border-radius: 8px;
            margin: 4px 8px;
            transition: all 0.3s ease;
        }

        .nav-sidebar .nav-link i {
            color: #374151 !important;
            font-size: 1.2rem;
        }

        .nav-sidebar .nav-link p {
            color: #374151 !important;
            font-size: 1rem;
            font-weight: 500;
        }

        .nav-sidebar .nav-item:hover > .nav-link {
            background-color: #f3f4f6;
            transform: translateX(5px);
        }

        .nav-sidebar .nav-link.active {
            background-color: #e5e7eb !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .brand-link {
            padding: 0;
            background: transparent !important;
        }

        .main-header {
            background-color: #ffffff !important;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .navbar-nav .btn-logout {
            color: #374151;
            font-weight: 500;
            padding: 0.5rem 1.2rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .navbar-nav .btn-logout:hover {
            background-color: #f3f4f6;
            color: #1f2937;
        }

        .navbar-nav .user-name {
            color: #374151;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }

        .nav-treeview .nav-link {
            padding-left: 2.5rem;
        }

        /* Estilos para os selects de unidade e calendário */
        #select-unidade,
        #select-calendario {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background-color: white;
            color: #374151;
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
            height: 32px;
            min-width: 200px;
            transition: all 0.3s ease;
        }

        #select-unidade:focus,
        #select-calendario:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .unidade-ativa,
        .calendario-ativo {
            font-size: 0.7rem;
            padding: 1px 4px;
            border-radius: 3px;
            background-color: #10b981;
            color: white;
            margin-left: 4px;
        }

        .unidade-visualizada,
        .calendario-visualizando {
            font-size: 0.7rem;
            padding: 1px 4px;
            border-radius: 3px;
            background-color: #3b82f6;
            color: white;
            margin-left: 4px;
        }

        .calendario-ano {
            font-size: 0.75rem;
            color: #6b7280;
            margin-left: 4px;
            font-weight: normal;
        }

        .nav-item .form-select-sm {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }
    </style>

    @yield('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
        
        <ul class="navbar-nav ms-auto">
           {{-- No layout app.blade.php --}}
<!-- Select de Calendários para Visualização -->
@if(isset($calendarios) && $calendarios->count() > 0) {{-- Mudei de $todosCalendarios para $calendarios --}}
<li class="nav-item">
    <div class="nav-link py-0 px-2">
        <select id="select-calendario-navbar" class="form-select form-select-sm"> {{-- Mudei o ID --}}
            <option value="">Selecione...</option>
            @foreach($calendarios as $calendario)
                @php
                    $isAtivo = $calendario->ativo;
                    $isVisualizando = session('calendario_visualizacao_id') == $calendario->id;
                @endphp
                <option value="{{ $calendario->id }}"
                    @if($isVisualizando) selected @endif
                    data-ativo="{{ $isAtivo ? '1' : '0' }}">
                    {{ $calendario->nomeCompleto }}
                    @if($isAtivo)
                        <span class="calendario-ativo">Ativo</span>
                    @endif
                    @if($isVisualizando)
                        <span class="calendario-visualizando">Visualizando</span>
                    @endif
                </option>
            @endforeach
        </select>
    </div>
</li>
@endif

          
            
            <!-- Nome do Usuário -->
            <li class="nav-item">
                <span class="nav-link user-name">
                    <i class="fas fa-user-circle me-2"></i>
                    @if (Auth::guard('web')->check())
                        {{ Auth::guard('web')->user()->name }}
                    @elseif (Auth::guard('professor')->check())
                        {{ Auth::guard('professor')->user()->nome }}
                    @else
                        Convidado
                    @endif
                </span>
            </li>
            
            <!-- Botão Sair -->
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="submit" class="nav-link btn btn-logout">
                        <i class="fas fa-sign-out-alt me-2"></i> Sair
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar elevation-4 sidebar-custom">
        <div class="brand-area">
            <a href="/" class="brand-link text-center">
                <img src="{{ asset('images/logoprefeitura.png') }}" alt="Logo Prefeitura" class="brand-logo">
                <span class="brand-text">SEDUC</span>
            </a>
        </div>
        <div class="sidebar">
            @include('layouts.navigation')
        </div>
    </aside>

    <!-- Conteúdo principal -->
    <div class="content-wrapper">
        @yield('content')
        {{ $slot ?? '' }}
        @livewireScripts
    </div>

    <!-- Rodapé -->
    <footer class="main-footer">
        <div class="float-end d-none d-sm-inline">SEDUC-Juazeiro-BA</div>
        <strong>© {{ date('Y') }} <a href="https://www.juazeiro.ba.gov.br/">Prefeitura Municipal de Juazeiro-BA</a>.</strong>
    </footer>
</div>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    console.log('=== INICIANDO SCRIPTS DO LAYOUT ===');

    // Troca de Calendário de Visualização (NAVBAR)
    $('#select-calendario-navbar').change(function() {
        console.log('Select calendário navbar alterado:', $(this).val());
        
        const calendarioId = $(this).val();
        if (!calendarioId) return;
        
        const select = $(this);
        const originalValue = select.val();
        select.prop('disabled', true);

        console.log('Enviando para:', '{{ route("calendario.visualizar") }}');
        
        $.ajax({
            url: '{{ route("calendario.visualizar") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                calendario_id: calendarioId
            },
            success: function(response) {
                console.log('Resposta:', response);
                if (response.success) {
                    console.log('Recarregando página...');
                    location.reload();
                } else {
                    Swal.fire('Erro!', response.message, 'error');
                    select.prop('disabled', false);
                    select.val(originalValue);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro AJAX:', error);
                Swal.fire({
                    title: 'Erro!', 
                    text: 'Não foi possível alterar o calendário. Verifique o console.',
                    icon: 'error'
                });
                select.prop('disabled', false);
                select.val(originalValue);
            }
        });
    });

    // Troca de Unidade de Visualização
    $('#select-unidade').change(function() {
        console.log('Select unidade alterado:', $(this).val());
        
        const unidadeId = $(this).val();
        if (!unidadeId) return;
        
        const select = $(this);
        const originalValue = select.val();
        select.prop('disabled', true);

        $.ajax({
            url: '{{ route("unidade.visualizar") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                unidade_id: unidadeId
            },
            success: function(response) {
                console.log('Resposta:', response);
                if (response.success) {
                    location.reload();
                } else {
                    Swal.fire('Erro!', response.message, 'error');
                    select.prop('disabled', false);
                    select.val(originalValue);
                }
            },
            error: function() {
                Swal.fire('Erro!', 'Não foi possível alterar a unidade.', 'error');
                select.prop('disabled', false);
                select.val(originalValue);
            }
        });
    });
    
    // Teste manual via console
    window.testarCalendario = function(id) {
        console.log('Testando calendário:', id);
        $('#select-calendario-navbar').val(id).trigger('change');
    };
    
    // Log para debug
    console.log('Select calendário encontrado:', $('#select-calendario-navbar').length);
    console.log('Select unidade encontrado:', $('#select-unidade').length);
    console.log('=== SCRIPTS DO LAYOUT CARREGADOS ===');
});
</script>
@stack('scripts')
</body>
</html>