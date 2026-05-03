<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Ingresso de Casamento - {{ $confirmacao->nome_completo }}</title>
    <style>
        @page {
            margin: 0px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: #fbf7f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            height: 100%;
            position: relative;
            padding: 40px;
            box-sizing: border-box;
            border: 1px solid #e1d3c1;
        }
        .border-inner {
            border: 2px solid #caaa7e;
            height: 96%;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
            background-color: #ffffff;
            border-radius: 10px;
        }
        .header {
            margin-top: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-family: 'Georgia', serif;
            font-size: 32px;
            color: #bd8e5b;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        .header p {
            font-size: 14px;
            color: #777;
            margin-top: 5px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .divider {
            width: 50%;
            height: 1px;
            background-color: #e1d3c1;
            margin: 20px auto;
        }
        .guest-info {
            margin: 40px 0;
        }
        .guest-info p {
            font-size: 14px;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 5px;
        }
        .guest-info h2 {
            font-family: 'Georgia', serif;
            font-size: 28px;
            color: #333;
            margin: 0;
        }
        .details {
            margin-top: 30px;
            font-size: 15px;
            line-height: 1.6;
            color: #555;
        }
        .details b {
            color: #333;
        }
        .ticket-code {
            margin-top: 50px;
            padding: 15px;
            background: #f9f9f9;
            border: 1px dashed #caaa7e;
            display: inline-block;
            border-radius: 8px;
        }
        .ticket-code span {
            display: block;
            font-size: 12px;
            color: #777;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .ticket-code strong {
            font-family: 'Courier New', Courier, monospace;
            font-size: 24px;
            color: #bd8e5b;
            letter-spacing: 2px;
        }
        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #aaa;
        }
        .status-badge {
            display: inline-block;
            margin-top: 15px;
            padding: 5px 15px;
            background-color: #d1e7dd;
            color: #0f5132;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="border-inner">
            <div class="header">
                <h1>Ingresso Individual</h1>
                <p>Acesso Exclusivo e Intransferível</p>
            </div>
            
            <div class="divider"></div>
            
            <div class="guest-info">
                <p>Convidado Presença Confirmada</p>
                <h2>{{ $confirmacao->nome_completo }}</h2>
                <div class="status-badge">Confirmado</div>
            </div>
            
            <div class="divider"></div>
            
            <div class="details">
                <p>Apresente este documento digitalmente ou impresso na recepção do evento.</p>
                <p><b>Importante:</b> Cada convidado registrado deve portar o seu acesso individual. Não é permitido repassar ou transferir a sua senha para outros.</p>
            </div>
            
            <div class="ticket-code">
                <span>Sua Senha Única de Acesso</span>
                <strong>{{ $confirmacao->senha_acesso }}</strong>
            </div>
            
            <div class="footer">
                <p>Aguardamos você para celebrar conosco!</p>
                <p>Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
