<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Presentes</title>
    <style>
        @page { margin: 0.8cm; }
        body { font-family: sans-serif; font-size: 9px; margin: 0; padding: 0; width: 100%; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; }
        th, td { border: 1px solid #eee; padding: 4px; text-align: left; overflow: hidden; word-wrap: break-word; }
        th { background-color: #f8f8f8; font-weight: bold; }
        h1 { text-align: center; font-size: 14px; margin-bottom: 5px; }
        .summary { margin-top: 10px; border: 1px solid #eee; padding: 8px; background: #fafafa; }
        .summary p { margin: 2px 0; }
        
        /* Column Widths */
        .col-nome { width: 35%; }
        .col-status { width: 30%; }
        .col-valor { width: 12%; }
        .col-metodo { width: 10%; }
        .col-data { width: 13%; }
    </style>
</head>
<body>
    <h1>Lista de Presentes e Apoiadores</h1>
    <p style="text-align: center;">Atualizado em {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>

    <div class="summary">
        <h4>Resumo:</h4>
        <p>Total de Presentes Ganhos: {{ count(array_filter($presentesLista, fn($p) => strpos($p['status'], 'Ganho') !== false)) }}</p>
        <p>Total Arrecadado: R$ {{ number_format($totalArrecadado, 2, ',', '.') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-nome">Presente / Experiência</th>
                <th class="col-status">Status / Apoiador</th>
                <th class="col-valor">Valor</th>
                <th class="col-metodo">Método</th>
                <th class="col-data">Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presentesLista as $p)
                <tr>
                    <td>{{ $p['nome'] }}</td>
                    <td>{{ $p['status'] }}</td>
                    <td>R$ {{ number_format($p['preco'], 2, ',', '.') }}</td>
                    <td style="text-transform: uppercase;">{{ $p['metodo'] ?? '-' }}</td>
                    <td>{{ $p['data'] ? \Carbon\Carbon::parse($p['data'])->format('d/m/Y H:i') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
