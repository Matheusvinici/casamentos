<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Presentes</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1, h3 { text-align: center; }
        .summary { margin-top: 20px; border: 1px solid #ddd; padding: 10px; width: 50%; }
    </style>
</head>
<body>
    <h1>Lista de Presentes e Apoiadores</h1>
    <p style="text-align: center;">Atualizado em {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>

    <div class="summary">
        <h4>Resumo:</h4>
        <p>Total de Presentes: {{ count($presentesRecebidos) }}</p>
        <p>Total Arrecadado: R$ {{ number_format($totalArrecadado, 2, ',', '.') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Presente / Experiência</th>
                <th>Apoiador</th>
                <th>Valor</th>
                <th>Método</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presentesRecebidos as $pr)
                <tr>
                    <td>{{ $pr['nome_presente'] }}</td>
                    <td>{{ $pr['usuario'] }}</td>
                    <td>R$ {{ number_format($pr['preco'], 2, ',', '.') }}</td>
                    <td style="text-transform: uppercase;">{{ $pr['metodo'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($pr['data_compra'])->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
