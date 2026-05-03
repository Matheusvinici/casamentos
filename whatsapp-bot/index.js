const { Client, LocalAuth, MessageMedia } = require('whatsapp-web.js');
const qrcodeTerminal = require('qrcode-terminal');
const qrcodeImage = require('qrcode');
const express = require('express');
const path = require('path');
const app = express();
const port = 3001;

app.use(express.json());

const client = new Client({
    authStrategy: new LocalAuth({
        dataPath: './.wwebjs_auth'
    }),
    puppeteer: {
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    }
});

client.on('qr', (qr) => {
    console.log('--- SCAN THE QR CODE BELOW ---');
    qrcodeTerminal.generate(qr, { small: true });
    
    // Gerar imagem do QR Code para visualização alternativa
    qrcodeImage.toFile(path.join(__dirname, 'qr.png'), qr, (err) => {
        if (err) console.error('Error saving QR image:', err);
        else console.log('QR Code image saved as qr.png');
    });
});

client.on('ready', () => {
    console.log('WhatsApp Client is READY!');
});

client.on('authenticated', () => {
    console.log('WhatsApp Authenticated!');
});

app.post('/send-message', async (req, res) => {
    const { number, message, pdfUrl, pdfName } = req.body;

    if (!number || !message) {
        return res.status(400).json({ status: 'error', message: 'Number and message are required' });
    }

    // Verifica se o cliente está conectado
    if (!client.info || !client.info.wid) {
        console.log('Tentativa de envio, mas o WhatsApp não está pronto.');
        return res.status(503).json({ status: 'error', message: 'WhatsApp Client is not ready. Please check the QR Code.' });
    }

    try {
        const formattedNumber = number.includes('@c.us') ? number : `${number}@c.us`;
        console.log(`Enviando para: ${formattedNumber}...`);
        
        // Enviar mensagem de texto
        await client.sendMessage(formattedNumber, message);

        // Se houver um PDF para enviar como arquivo
        if (pdfUrl) {
            try {
                console.log(`Baixando PDF de: ${pdfUrl}`);
                const media = await MessageMedia.fromUrl(pdfUrl);
                media.filename = pdfName || 'Ingresso_Casamento.pdf';
                await client.sendMessage(formattedNumber, media);
                console.log(`PDF enviado com sucesso para ${number}`);
            } catch (pdfError) {
                console.error('Erro ao processar/enviar arquivo PDF:', pdfError.message);
            }
        }

        res.json({ status: 'success', message: 'Message sent' });
    } catch (error) {
        console.error('Error sending message:', error);
        res.status(500).json({ status: 'error', message: error.message });
    }
});

client.initialize();

app.listen(port, () => {
    console.log(`WhatsApp Bot API listening at http://localhost:${port}`);
});
