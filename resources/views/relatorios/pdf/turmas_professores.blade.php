<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Turmas dos Professores</title>
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
            Relatório de Turmas dos Professores
        </div>
        <div class="subtitle">
            @if($professorId)
                Professor: {{ $professores->firstWhere('id', $professorId)->nome }}
            @else
                Todos os Professores
            @endif
        </div>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>Professor</th>
                <th>Turma</th>
                <th>Curso</th>
                <th>Nível</th>
                <th>Turno</th>
            </tr>
        </thead>
        <tbody>
            @foreach($turmas as $turma)
                <tr>
                    <td>{{ $turma->professor ? $turma->professor->nome : 'Sem professor' }}</td>
                    <td>{{ $turma->nome }}</td>
                    <td>{{ $turma->curso->nome }}</td>
                    <td>{{ $turma->nivel->nome }}</td>
                    <td>{{ $turma->turno->nome }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        Gerado em: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>