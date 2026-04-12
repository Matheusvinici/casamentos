<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Localidades dos Alunos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 150px;
            margin: 10px 0;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #005566;
        }
        .subtitle {
            font-size: 16px;
            color: #555;
            margin-top: 5px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #005566;
            color: white;
            font-weight: bold;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logoSeduc.jpeg') }}" alt="Logo Secretaria de Educação">
        <div class="title">Secretaria de Educação</div>
        <div class="title">Escola de Idiomas</div>
        <div class="subtitle">
            Relatório de Localidades dos Alunos
        </div>
        <div class="subtitle">
            @if($bairroId)
                Bairro: {{ $bairros->firstWhere('id', $bairroId)->nome }}
            @else
                Todos os Bairros
            @endif
        </div>
    </div>
    
    <div class="section-title">Resumo por Bairro</div>
    <table class="table">
        <thead>
            <tr>
                <th>Bairro</th>
                <th>Quantidade de Alunos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alunosPorBairro as $bairro => $quantidade)
                <tr>
                    <td>{{ $bairro }}</td>
                    <td>{{ $quantidade }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="section-title">Detalhes dos Alunos</div>
    <table class="table">
        <thead>
            <tr>
                <th>Aluno</th>
                <th>Bairro</th>
                <th>Cidade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alunos as $aluno)
                <tr>
                    <td>{{ $aluno->nome }}</td>
                    <td>{{ $aluno->bairro->nome }}</td>
                    <td>{{ $aluno->cidade->nome }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        Gerado em: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>