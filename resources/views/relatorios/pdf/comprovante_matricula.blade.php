<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Declaração de Matrícula</title>
    <style>
        @page {
            margin: 2cm;
            size: A4;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background: white;
        }
        
        /* Fundo como elemento fixo */
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        
        .background img {
            width: 500px;
            height: auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.08;
        }
        
        /* Container principal */
        .container {
            width: 100%;
            position: relative;
            z-index: 1;
        }
        
        /* Tabela principal */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .main-table td {
            padding: 5px;
            vertical-align: top;
        }
        
        /* Cabeçalho */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .header-table td {
            border: none;
            padding: 5px;
            vertical-align: middle;
        }
        
        .logo-left {
            width: 100px;
            text-align: left;
        }
        
        .logo-left img {
            height: 70px;
            width: auto;
        }
        
        .header-center {
            text-align: center;
        }
        
        .header-center .secretaria {
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            color: #001866;
        }
        
        .header-center .superintendencia {
            font-size: 10px;
            font-weight: bold;
            color: #005566;
        }
        
        .header-center .escola {
            font-size: 11px;
            font-weight: bold;
            color: #001866;
            margin-top: 3px;
            text-transform: uppercase;
        }
        
        .logo-right {
            width: 100px;
            text-align: right;
        }
        
        .logo-right img {
            height: 70px;
            width: auto;
        }
        
        /* Título */
        .title {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            text-transform: uppercase;
            color: #001866;
        }
        
        /* Texto da declaração */
        .declaration-text {
            font-size: 14px;
            line-height: 1.6;
            text-align: justify;
            margin: 15px 0;
        }
        
        .declaration-text p {
            margin: 10px 0;
        }
        
        .student-info {
            font-weight: bold;
            color: #001866;
        }
        
        /* Dias de aula */
        .dias-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #f9f9f9;
            border-left: 4px solid #001866;
        }
        
        .dias-table td {
            padding: 10px 15px;
        }
        
        .dias-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 11px;
        }
        
        .checkboxes {
            margin-top: 10px;
        }
        
        .checkbox-item {
            display: inline-block;
            margin-right: 25px;
            margin-bottom: 5px;
            font-size: 11px;
        }
        
        .checkbox-quadrado {
            width: 14px;
            height: 14px;
            border: 1px solid #333;
            display: inline-block;
            margin-right: 5px;
            background: white;
        }
        
        .checkbox-marcado {
            background: #001866;
        }
        
        /* Assinatura */
        .signature-table {
            width: 100%;
            margin-top: 40px;
        }
        
        .signature-table td {
            text-align: right;
        }
        
        .signature-box {
            text-align: center;
            width: 250px;
            display: inline-block;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 35px;
            padding-top: 8px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .signature-cargo {
            font-size: 9px;
            margin-top: 5px;
            color: #555;
        }
        
        /* Rodapé */
        .footer-table {
            width: 100%;
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        
        .footer-table td {
            font-size: 8px;
            color: #777;
            text-align: center;
        }
        
        .protocolo {
            font-size: 8px;
            color: #777;
            text-align: right;
            margin-top: 15px;
        }
        
        .data {
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
        
        .lei {
            font-size: 9px;
            color: #777;
            text-align: center;
            margin-top: 5px;
        }
        
        .siej {
            font-size: 8px;
            color: #666;
            text-align: center;
            margin-top: 10px;
        }
        
        .warning {
            font-size: 9px;
            color: #999;
            margin-top: 5px;
            font-style: italic;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Fundo fixo com a logo -->
<div class="background">
    <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents(public_path('images/Logo_Idiomas.png'))); ?>" alt="Logo">
</div>

<div class="container">
    <!-- Tabela do cabeçalho -->
    <table class="header-table">
        <tr>
            <td class="logo-left">
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents(public_path('images/Logo_Idiomas.png'))); ?>" alt="Escola de Idiomas">
            </td>
            <td class="header-center">
                <div class="secretaria">SECRETARIA DE EDUCAÇÃO</div>
                <div class="superintendencia">SUPERINTENDÊNCIA PEDAGÓGICA</div>
                <div class="escola">ESCOLA DE IDIOMAS – Juazeiro-BA </div>
            </td>
            <td class="logo-right">
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents(public_path('images/logoSeduc.jpeg'))); ?>" alt="SEDUC">
            </td>
        </tr>
    </table>
 <br> <br> <br> <br> <br>
    <!-- Título -->
    <div class="title">DECLARAÇÃO</div>
    <br> <br> <br> <br> <br> <br> <br><br> <br><br><br> <br>
    <!-- Texto da declaração -->
    <div class="declaration-text">
        <p>
            Declaramos que o(a) estudante: 
            <span class="student-info">{{ strtoupper($matricula->aluno->nome) }}</span>, 
            nascido(a) em 
            <span class="student-info">{{ \Carbon\Carbon::parse($matricula->aluno->data_nascimento)->format('d/m/Y') }}</span>,
            <!-- CPF nº  -->
            <!-- <span class="student-info">{{ $matricula->aluno->aluno_cpf ?? '____________________' }}</span>, -->
            está <strong>REGULARMENTE MATRICULADO(A)</strong> no curso de 
            <span class="student-info">{{ strtoupper($matricula->turma->curso->nome ?? 'N/A') }}</span> 
            no Turno 
            <span class="student-info">{{ strtoupper($matricula->turma->turno->nome ?? 'N/A') }}</span> 
            na <strong>Escola de Idiomas</strong>, vinculada à Rede Municipal de Ensino de Juazeiro-BA.
        </p>
        
        <p>
           
            O(a) aluno(a) frequenta regularmente as aulas nos dias de:
        </p>
    </div>
    <br> <br> 
    <!-- Dias de aula usando tabela -->
    <table class="dias-table">
        <tr>
            <td>
                <div class="dias-title">DIAS DE AULA NA SEMANA:</div>
                <div class="checkboxes">
                    @php
                        // Buscar os dias de aula da turma na tabela letivos
                        $diasLetivos = \App\Models\Letivo::where('turma_id', $matricula->turma_id)
                            ->select('dia')
                            ->distinct()
                            ->orderByRaw("FIELD(dia, 'SEGUNDA', 'TERCA', 'QUARTA', 'QUINTA', 'SEXTA', 'SABADO', 'DOMINGO')")
                            ->pluck('dia')
                            ->toArray();
                        
                        $mapaDias = [
                            'SEGUNDA' => 'SEG',
                            'TERCA' => 'TER',
                            'QUARTA' => 'QUA',
                            'QUINTA' => 'QUI',
                            'SEXTA' => 'SEX',
                            'SABADO' => 'SAB',
                            'DOMINGO' => 'DOM'
                        ];
                        
                        $diasAbreviados = [];
                        foreach ($diasLetivos as $dia) {
                            $diaUpper = strtoupper($dia);
                            if (isset($mapaDias[$diaUpper])) {
                                $diasAbreviados[] = $mapaDias[$diaUpper];
                            } else {
                                $diasAbreviados[] = substr($diaUpper, 0, 3);
                            }
                        }
                        
                        $todosDias = ['SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SAB', 'DOM'];
                    @endphp
                    
                    @foreach($todosDias as $dia)
                        <div class="checkbox-item">
                            <span class="checkbox-quadrado {{ in_array($dia, $diasAbreviados) ? 'checkbox-marcado' : '' }}"></span>
                            {{ $dia }}
                        </div>
                    @endforeach
                </div>
                @if(empty($diasAbreviados))
                    <div class="warning">* Dias de aula não cadastrados no sistema</div>
                @endif
            </td>
        </tr>
    </table>

    <!-- Assinatura usando tabela -->
    <table class="signature-table">
        <tr>
            <td class="text-right">
                <div class="signature-box">
                    <div class="signature-line">
                        Escola de Idiomas - Juazeiro-BA
                        <br>
                        Lei. 3077/2022
                    </div>
                   
                </div>
            </td>
        </tr>
    </table>

    <!-- Rodapé -->
    <table class="footer-table">
        <tr>
            <td class="text-center">
                <div class="protocolo">
                    <strong>Protocolo:</strong> {{ $matricula->id }}{{ date('Ymd') }}{{ str_pad($matricula->aluno->id, 4, '0', STR_PAD_LEFT) }} | 
                    <strong>Emissão:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
                </div>
                
                <div class="data">
    <strong>Juazeiro-BA, {{ \Carbon\Carbon::now()->format('d') }} de 
    @php
        $meses = [
            'January' => 'Janeiro',
            'February' => 'Fevereiro',
            'March' => 'Março',
            'April' => 'Abril',
            'May' => 'Maio',
            'June' => 'Junho',
            'July' => 'Julho',
            'August' => 'Agosto',
            'September' => 'Setembro',
            'October' => 'Outubro',
            'November' => 'Novembro',
            'December' => 'Dezembro'
        ];
        $mesNumero = \Carbon\Carbon::now()->format('F');
        $mesPortugues = $meses[$mesNumero];
    @endphp
    {{ $mesPortugues }} de {{ \Carbon\Carbon::now()->format('Y') }}</strong>
</div>
                
                <div class="lei">
                    Escola de Idiomas – Juazeiro-BA 
                </div>
                
                <div class="siej">
                    SIEJ - Secretaria de Educação de Juazeiro-BA
                </div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>