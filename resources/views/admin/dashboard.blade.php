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
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
                <h3 class="admin-table-title border-bottom-0 pb-0 mb-0"><i class="fas fa-list-ul me-2"></i> Lista de Confirmações (RSVP)</h3>
                <div>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#bulkWhatsappModal">
                        <i class="fab fa-whatsapp"></i> Enviar Convites (Whats)
                    </button>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addConfirmacaoModal">
                        <i class="fas fa-plus"></i> Adicionar
                    </button>
                    <a href="{{ route('admin.casamento.relatorio.confirmacoes') }}" class="btn btn-sm btn-danger" target="_blank">
                        <i class="fas fa-file-pdf"></i> Gerar PDF
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-custom table-hover">
                    <thead>
                        <tr>
                            <th>Nome do Convidado</th>
                            <th>Confirmado Por (Hóspede)</th>
                            <th>Datação</th>
                            <th>Status</th>
                            <th>Ações</th>
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
                            <td>
                                <div class="d-flex" style="gap: 5px;">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editConfirmacaoModal{{ $p->id }}" title="Editar Convidado">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <form action="{{ route('admin.casamento.confirmacao.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Excluir esta confirmação permanentemente?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir Convidado">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                    @if($p->status == 'confirmado')
                                    <div class="d-flex" style="gap: 5px;">
                                        <form action="{{ route('admin.casamento.disparar.individual.bot', $p->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Enviar Convite Automático via Robô">
                                                <i class="fab fa-whatsapp"></i>
                                            </button>
                                        </form>

                                        <a href="{{ route('convite.individual.pdf', ['id' => $p->id, 'senha' => $p->senha_acesso ?? 'no-password']) }}" class="btn btn-sm btn-outline-danger" target="_blank" title="Ver PDF Individual">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>

                                        @php
                                            $foneManual = $p->user ? preg_replace('/[^0-9]/', '', $p->user->phone1 ?? $p->user->phone2) : '';
                                            if ($foneManual && substr($foneManual, 0, 2) != '55') $foneManual = '55' . $foneManual;
                                            $linkIndividual = route('convite.individual.pdf', ['id' => $p->id, 'senha' => $p->senha_acesso ?? 'no-password']);
                                            $msgIndividual = urlencode("Olá! Aqui está o seu ingresso individual para o casamento de Mary & Matheus.\n\nSua Senha: " . ($p->senha_acesso ?? 'Gerando...') . "\n\nLink do PDF: " . $linkIndividual);
                                            $zapManualUrl = "https://api.whatsapp.com/send?phone={$foneManual}&text={$msgIndividual}";
                                        @endphp
                                        <a href="{{ $zapManualUrl }}" target="_blank" class="btn btn-sm btn-outline-success" title="Enviar Link Manualmente via WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Edit Confirmação -->
                        <div class="modal fade" id="editConfirmacaoModal{{ $p->id }}" tabindex="-1" aria-labelledby="editConfirmacaoModalLabel{{ $p->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.casamento.confirmacao.update', $p->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editConfirmacaoModalLabel{{ $p->id }}">Editar Confirmação</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="nome_completo_{{ $p->id }}" class="form-label">Nome Completo</label>
                                                <input type="text" class="form-control" id="nome_completo_{{ $p->id }}" name="nome_completo" value="{{ $p->nome_completo }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="status_{{ $p->id }}" class="form-label">Status</label>
                                                <select class="form-select" id="status_{{ $p->id }}" name="status" required>
                                                    <option value="confirmado" {{ $p->status == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                                                    <option value="desistiu" {{ $p->status == 'desistiu' ? 'selected' : '' }}>Desistiu</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Fim Modal -->
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Nenhuma confirmação de presença registrada ainda.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Presentes Table -->
        <div class="admin-table-card">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
                <h3 class="admin-table-title border-bottom-0 pb-0 mb-0"><i class="fas fa-box-open me-2"></i> Lista de Presentes e Apoiadores</h3>
                <a href="{{ route('admin.casamento.relatorio.presentes') }}" class="btn btn-sm btn-danger" target="_blank">
                    <i class="fas fa-file-pdf"></i> Gerar PDF
                </a>
            </div>
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
                                <div class="d-flex" style="gap: 5px;">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPresenteModal{{ $pr['id'] }}" title="Editar Nome do Presente">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <a href="{{ route('admin.casamento.agradecimento.presente', $pr['id']) }}" class="btn btn-sm btn-outline-danger" target="_blank" title="Gerar PDF Agradecimento">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>

                                    @php
                                        $msgGift = urlencode("Olá {$pr['usuario']}! Passando para agradecer imensamente pelo seu presente: *{$pr['nome_presente']}*. Seu carinho nos deixa muito felizes! ❤️");
                                        // Tenta pegar o telefone do usuário se existir
                                        $userObj = \App\Models\User::find($presencas->where('user_id', '!=', null)->where('nome_completo', $pr['usuario'])->first()->user_id ?? 0);
                                        $foneGift = $userObj ? preg_replace('/[^0-9]/', '', $userObj->phone1 ?? $userObj->phone2) : '';
                                        if ($foneGift && substr($foneGift, 0, 2) != '55') $foneGift = '55' . $foneGift;
                                        $zapGiftUrl = "https://api.whatsapp.com/send?phone={$foneGift}&text={$msgGift}";
                                    @endphp
                                    <a href="{{ $zapGiftUrl }}" target="_blank" class="btn btn-sm btn-outline-success" title="Enviar Agradecimento via WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>

                                    <form action="{{ route('admin.casamento.presente.desbloquear', $pr['id']) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja desbloquear este presente?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Desbloquear Presente">
                                            <i class="fas fa-unlock"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Modal Editar Presente -->
                                <div class="modal fade" id="editPresenteModal{{ $pr['id'] }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.casamento.presente.update', $pr['id']) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Editar Nome do Presente</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nome do Presente (Override)</label>
                                                        <input type="text" class="form-control" name="nome_manual" value="{{ $pr['nome_manual'] ?? '' }}" placeholder="{{ $pr['nome_presente'] }}">
                                                        <small class="text-muted">Deixe em branco para usar o nome padrão.</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Convidado que deu (Override)</label>
                                                        <input type="text" class="form-control" name="convidado_manual" value="{{ $pr['convidado_manual'] ?? '' }}" placeholder="{{ $pr['usuario'] }}">
                                                        <small class="text-muted">Utilize para alterar manualmente quem deu o presente.</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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

        <!-- Modal Adicionar Confirmação -->
        <div class="modal fade" id="addConfirmacaoModal" tabindex="-1" aria-labelledby="addConfirmacaoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.casamento.confirmacao.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addConfirmacaoModalLabel">Adicionar Nova Confirmação</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nome_completo" class="form-label">Nome Completo do Convidado</label>
                                <input type="text" class="form-control" id="nome_completo" name="nome_completo" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="confirmado">Confirmado</option>
                                    <option value="desistiu">Desistiu</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-success">Adicionar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal Bulk WhatsApp -->
        <div class="modal fade" id="bulkWhatsappModal" tabindex="-1" aria-labelledby="bulkWhatsappModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bulkWhatsappModalLabel">
                            <i class="fab fa-whatsapp text-success"></i> Disparos Manuais de WhatsApp
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted"><small>Clique nos links abaixo para abrir o WhatsApp Web/App com a mensagem pronta para cada convidado.</small></p>
                        <div class="list-group">
                            @forelse($presencas->where('status', 'confirmado') as $p)
                                @php
                                    $telefoneBulk = $p->user ? preg_replace('/[^0-9]/', '', $p->user->phone1 ?? $p->user->phone2) : '';
                                    if ($telefoneBulk && substr($telefoneBulk, 0, 2) != '55') $telefoneBulk = '55' . $telefoneBulk;
                                    $linkPdfBulk = route('convite.individual.pdf', ['id' => $p->id, 'senha' => $p->senha_acesso ?? '0000']);
                                    $textoZapBulk = urlencode("Olá! Aqui está o ingresso individual e intransferível para o nosso casamento.\n\n*Convidado(a):* {$p->nome_completo}\n*Sua Senha de Acesso:* {$p->senha_acesso}\n\nApresente a senha acima ou baixe e mostre o PDF na entrada do evento para liberar seu acesso:\n{$linkPdfBulk}\n\nEstamos muito felizes em ter você com a gente!");
                                    $whatsUrl = "https://api.whatsapp.com/send?phone={$telefoneBulk}&text={$textoZapBulk}";
                                @endphp
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $p->nome_completo }}</strong><br>
                                        <small class="text-muted">ID: {{ $p->id }} | Senha: {{ $p->senha_acesso }} | Fone: {{ $telefoneBulk ?: 'Não preenchido' }}</small>
                                    </div>
                                    <form action="{{ route('admin.casamento.disparar.individual.bot', $p->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fab fa-whatsapp"></i> Enviar Agora
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="list-group-item text-center">Nenhum convidado confirmado para enviar.</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <form action="{{ route('admin.casamento.disparar.massa') }}" method="POST" onsubmit="return confirm('Deseja iniciar o disparo automático para todos os confirmados? Verifique se o Robô Node.js está rodando e autenticado.');">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-robot"></i> Disparar para Todos (Automático)
                            </button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fim Modal -->

    </div>
</div>
@endsection
