@extends('layouts.casamento')

@section('title', 'Painel Administrativo - Casamento')

@push('styles')
<style>
    .admin-page {
        padding: 4rem 0;
        background: #f8f9fc;
        min-height: calc(100vh - 140px);
    }
    .admin-title {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: var(--text-dark);
        margin-bottom: 2rem;
    }
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: none;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }
    .icon-green { background: #d1e7dd; color: #0f5132; }
    .icon-red { background: #f8d7da; color: #842029; }
    .icon-gold { background: rgba(201,168,124,0.2); color: var(--rose-dark); }
    
    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
        line-height: 1;
    }
    .stat-label {
        color: var(--text-light);
        font-size: 0.9rem;
        margin: 0;
    }

    .admin-table-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }
    .admin-table-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: var(--text-dark);
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }
    .table-custom {
        width: 100%;
    }
    .table-custom th {
        background: #f8f9fc;
        padding: 1rem;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .table-custom td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #eee;
        color: #555;
    }
    .badge-admin {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-success { background: #d1e7dd; color: #0f5132; }
    .badge-danger { background: #f8d7da; color: #842029; }
</style>
@endpush

@section('content')
<div class="admin-page">
    <div class="container">
        <h1 class="admin-title"><i class="fas fa-crown me-2" style="color: var(--rose);"></i> Painel Administrativo</h1>
        
        <!-- Stats Row -->
        <div class="row">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon icon-green"><i class="fas fa-user-check"></i></div>
                    <div>
                        <p class="stat-value">{{ $totalConfirmados }}</p>
                        <p class="stat-label">Convidados Confirmados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon icon-red"><i class="fas fa-user-times"></i></div>
                    <div>
                        <p class="stat-value">{{ $totalDesistentes }}</p>
                        <p class="stat-label">Desistências</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon icon-gold"><i class="fas fa-gift"></i></div>
                    <div>
                        <p class="stat-value">R$ {{ number_format($totalArrecadado, 2, ',', '.') }}</p>
                        <p class="stat-label">Total Arrecadado ({{ count($presentesRecebidos) }} presentes)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Presenças Table -->
        <div class="admin-table-card">
            <h3 class="admin-table-title"><i class="fas fa-list-ul me-2"></i> Lista de Confirmações (RSVP)</h3>
            <div class="table-responsive">
                <table class="table table-custom table-hover">
                    <thead>
                        <tr>
                            <th>Nome do Convidado</th>
                            <th>Confirmado Por (Hóspede)</th>
                            <th>Datação</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presencas as $p)
                        <tr>
                            <td style="font-weight: 500; color: var(--text-dark);">{{ $p->nome_completo }}</td>
                            <td>{{ $p->user ? $p->user->name : 'N/A' }} <br><small class="text-muted">{{ $p->user ? $p->user->email : '' }}</small></td>
                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($p->status == 'confirmado')
                                    <span class="badge-admin badge-success">Confirmado</span>
                                @else
                                    <span class="badge-admin badge-danger">Desistiu</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Nenhuma confirmação de presença registrada ainda.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Presentes Table -->
        <div class="admin-table-card">
            <h3 class="admin-table-title"><i class="fas fa-box-open me-2"></i> Lista de Presentes e Apoiadores</h3>
            <div class="table-responsive">
                <table class="table table-custom table-hover">
                    <thead>
                        <tr>
                            <th>Presente / Experiência</th>
                            <th>Apoiador (Hóspede)</th>
                            <th>Valor</th>
                            <th>Método</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presentesRecebidos as $pr)
                        <tr>
                            <td style="font-weight: 500; color: var(--text-dark);">{{ $pr['nome_presente'] }}</td>
                            <td>{{ $pr['usuario'] }}</td>
                            <td style="color: var(--rose-dark); font-weight: 600;">R$ {{ number_format($pr['preco'], 2, ',', '.') }}</td>
                            <td><span style="text-transform: uppercase; font-size: 0.8rem; font-weight: bold; color: #888;"><i class="fas {{ $pr['metodo'] == 'pix' ? 'fa-qrcode' : 'fa-credit-card' }}"></i> {{ $pr['metodo'] }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($pr['data_compra'])->format('d/m/Y H:i') }}</td>
                            <td>
                                <form action="{{ route('admin.casamento.presente.desbloquear', $pr['id']) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja desbloquear este presente?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Desbloquear Presente">
                                        <i class="fas fa-unlock"></i> Desbloquear
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Nenhum presente registrado ainda.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
