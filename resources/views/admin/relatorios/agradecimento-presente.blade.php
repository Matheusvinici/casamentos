<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Agradecimento - {{ $nomeConvidado }}</title>
    <style>
        @page { margin: 1.5cm; }
        * { box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background: #fff; color: #333; margin: 0; padding: 0; text-align: center; width: 100%; }
        .card { border: 2px solid #caaa7e; padding: 30px; border-radius: 15px; background: #fff; position: relative; width: 95%; margin: 0 auto; }
        h1 { font-family: 'Georgia', serif; font-size: 24px; color: #bd8e5b; margin-top: 0; }
        .names { font-family: 'Georgia', serif; font-size: 18px; color: #555; margin-bottom: 20px; font-style: italic; }
        .message { font-size: 14px; line-height: 1.5; color: #444; margin: 20px 0; }
        .gift-info { font-weight: bold; color: #bd8e5b; font-size: 16px; margin: 15px 0; }
        .footer { margin-top: 30px; font-size: 12px; color: #888; }
        .divider { width: 40px; height: 1px; background: #caaa7e; margin: 15px auto; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Muito Obrigado!</h1>
        <div class="names">Mary & Matheus</div>
        
        <div class="message">
            Querido(a) <strong>{{ $nomeConvidado }}</strong>,<br><br>
            Ficamos imensamente felizes com o seu carinho e generosidade. 
            Seu presente tornará nossa vida e nossa comemoração ainda mais especial.
        </div>
        
        <div class="divider"></div>
        
        <div class="gift-info">
            {{ $nomePresente }}
        </div>
        
        <div class="divider"></div>
        
        <div class="footer">
            Com amor,<br>
            <strong>Mary & Matheus</strong><br>
            <small>06 de Junho de 2026</small>
        </div>
    </div>
</body>
</html>
