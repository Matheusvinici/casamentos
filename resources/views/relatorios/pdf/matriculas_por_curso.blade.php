<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Matrículas por Curso</title>
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
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            color: white;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-danger {
            background-color: #dc3545;
        }
        .badge-warning {
            background-color: #ffc107;
            color: black;
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
            Relatório de Matrículas por Curso
        </div>
        <div class="subtitle">
            @if($cursoId)
                Curso: {{ $cursos->firstWhere('id', $cursoId)->nome }}
            @else
                Todos os Cursos
            @endif
            | Status: {{ ucfirst($status) }}
        </div>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>Aluno</th>
                <th>Turma</th>
                <th>Curso</th>
                <th>Data Matrícula</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matriculas as $matricula)
                <tr>
                    <td>{{ $matricula->aluno->nome }}</td>
                    <td>{{ $matricula->turma->nome }}</td>
                    <td>{{ $matricula->turma->curso->nome }}</td>
                    <td>{{ \Carbon\Carbon::parse($matricula->data_matricula)->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge badge-{{ $matricula->status == 'ativo' ? 'success' : ($matricula->status == 'inativo' ? 'danger' : 'warning') }}">
                            {{ ucfirst($matricula->status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        Gerado em: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>