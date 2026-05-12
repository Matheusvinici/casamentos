<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Ingresso de Casamento - {{ $confirmacao->nome_completo }}</title>
    <style>
        @page {
            margin: 1cm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: #ffffff;
            color: #333;
            margin: 0;
            padding: 0;
            width: 100%;
            text-align: center;
        }
        .border-inner {
            border: 2px solid #caaa7e;
            padding: 15px;
            box-sizing: border-box;
            text-align: center;
            background-color: #ffffff;
            border-radius: 10px;
            width: 95%;
            margin: 0 auto;
        }
        .header {
            margin-top: 10px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-family: 'Georgia', serif;
            font-size: 24px;
            color: #bd8e5b;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header p {
            font-size: 12px;
            color: #777;
            margin-top: 5px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .divider {
            width: 50%;
            height: 1px;
            background-color: #e1d3c1;
            margin: 15px auto;
        }
        .guest-info {
            margin: 20px 0;
        }
        .guest-info p {
            font-size: 12px;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 3px;
        }
        .guest-info h2 {
            font-family: 'Georgia', serif;
            font-size: 22px;
            color: #333;
            margin: 0;
        }
        .details {
            margin-top: 15px;
            font-size: 13px;
            line-height: 1.4;
            color: #555;
        }
        .ticket-code {
            margin-top: 25px;
            padding: 10px;
            background: #f9f9f9;
            border: 1px dashed #caaa7e;
            display: inline-block;
            border-radius: 8px;
        }
        .ticket-code span {
            display: block;
            font-size: 10px;
            color: #777;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .ticket-code strong {
            font-family: 'Courier New', Courier, monospace;
            font-size: 20px;
            color: #bd8e5b;
            letter-spacing: 2px;
        }
        .footer {
            margin-top: 25px;
            font-size: 11px;
            color: #aaa;
        }
        .status-badge {
            display: inline-block;
            margin-top: 10px;
            padding: 3px 10px;
            background-color: #d1e7dd;
            color: #0f5132;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
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
            <p><b>Data:</b> 06 de Junho de 2026 | <b>Cerimônia:</b> 17h00</p>
            <p><b>Local:</b> Paróquia São José Operário, Petrolina - PE</p>
            <p><b>Recepção:</b> 19h00 - Restaurante Bêra Dágua</p>
            <div class="divider" style="width: 20%; margin: 10px auto;"></div>
            <p style="font-size: 11px;">Apresente este ingresso na entrada do evento.</p>
            <p style="font-size: 11px; margin-top: 5px;">Este convite é individual e intransferível.</p>
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
</body>
</html>
