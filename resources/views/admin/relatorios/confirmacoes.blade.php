<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Confirmações</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1, h3 { text-align: center; }
        .success { color: green; font-weight: bold; }
        .danger { color: red; font-weight: bold; }
        .summary { margin-top: 20px; border: 1px solid #ddd; padding: 10px; width: 50%; }
    </style>
</head>
<body>
    <h1>Lista de Confirmações (RSVP)</h1>
    <p style="text-align: center;">Atualizado em {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>

    <div class="summary">
        <h4>Resumo:</h4>
        <p>Total Confirmados: {{ $totalConfirmados }}</p>
        <p>Total Desistentes: {{ $totalDesistentes }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nome do Convidado</th>
                <th>Status</th>
                <th>Data da Confirmação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($confirmacoes as $p)
                <tr>
                    <td>{{ $p->nome_completo }}</td>
                    <td>
                        @if($p->status == 'confirmado')
                            <span class="success">Confirmado</span>
                        @else
                            <span class="danger">Desistiu</span>
                        @endif
                    </td>
                    <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
