<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\PresenteComprado;

class PresenteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Lista de 15 presentes hardcoded
     */
    public static function getPresentes()
    {
        return [
            1 => [
                'id' => 1,
                'nome' => 'Jardim Botânico de Curitiba',
                'cidade' => 'Curitiba',
                'descricao' => 'Visita guiada ao icônico Jardim Botânico com sua estufa Art Nouveau, jardins franceses e trilhas ecológicas.',
                'preco' => 320.00,
                'imagem' => 'https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?w=600',
            ],
            2 => [
                'id' => 2,
                'nome' => 'Tour Gastronômico Curitibano',
                'cidade' => 'Curitiba',
                'descricao' => 'Roteiro exclusivo pelos melhores restaurantes e cafés coloniais, degustando pratos típicos paranaenses.',
                'preco' => 400.00,
                'imagem' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600',
            ],
            3 => [
                'id' => 3,
                'nome' => 'Passeio de Trem – Serra do Mar',
                'cidade' => 'Curitiba',
                'descricao' => 'Viagem inesquecível de trem pela Serra do Mar, com vistas da Mata Atlântica e pontes históricas.',
                'preco' => 521.00,
                'imagem' => 'https://images.unsplash.com/photo-1474487548417-781cb71495f3?w=600',
            ],
            4 => [
                'id' => 4,
                'nome' => 'Museu Oscar Niemeyer + Ópera de Arame',
                'cidade' => 'Curitiba',
                'descricao' => 'Tour cultural pelo Museu do Olho e o teatro Ópera de Arame, ícones arquitetônicos de Curitiba.',
                'preco' => 350.00,
                'imagem' => 'https://images.unsplash.com/photo-1518998053901-5348d3961a04?w=600',
            ],
            5 => [
                'id' => 5,
                'nome' => 'Spa Day para Casal em Curitiba',
                'cidade' => 'Curitiba',
                'descricao' => 'Dia de relaxamento completo com massagem, sauna, ofurô e tratamentos especiais para o casal.',
                'preco' => 900.00,
                'imagem' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=600',
            ],
            6 => [
                'id' => 6,
                'nome' => 'Teleférico + Cristo Luz',
                'cidade' => 'Balneário Camboriú',
                'descricao' => 'Passeio de teleférico com vista panorâmica e visita ao mirante do Cristo Luz ao entardecer.',
                'preco' => 421.00,
                'imagem' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600',
            ],
            7 => [
                'id' => 7,
                'nome' => 'Passeio de Barco Pirata',
                'cidade' => 'Balneário Camboriú',
                'descricao' => 'Navegação divertida a bordo do Barco Pirata pela costa, com música, animação e paisagens.',
                'preco' => 350.00,
                'imagem' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=600',
            ],
            8 => [
                'id' => 8,
                'nome' => 'Day Use Beach Club Premium',
                'cidade' => 'Balneário Camboriú',
                'descricao' => 'Dia completo em beach club VIP com piscina infinita, drinks artesanais e vista panorâmica.',
                'preco' => 513.00,
                'imagem' => 'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=600',
            ],
            9 => [
                'id' => 9,
                'nome' => 'Roda Gigante FG Big Wheel',
                'cidade' => 'Balneário Camboriú',
                'descricao' => 'Experiência na maior roda-gigante do Brasil com cabine exclusiva e vista 360° ao pôr do sol.',
                'preco' => 380.00,
                'imagem' => 'https://picsum.photos/seed/rodagigante/600/400',
            ],
            10 => [
                'id' => 10,
                'nome' => 'Jantar Premium Beira-Mar BC',
                'cidade' => 'Balneário Camboriú',
                'descricao' => 'Jantar romântico em restaurante renomado à beira-mar com menu degustação e harmonização de vinhos.',
                'preco' => 1200.00,
                'imagem' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=600',
            ],
            11 => [
                'id' => 11,
                'nome' => 'Trilha da Lagoinha do Leste',
                'cidade' => 'Florianópolis',
                'descricao' => 'Trilha até uma das praias mais bonitas e preservadas do Brasil, com paisagens de tirar o fôlego.',
                'preco' => 320.00,
                'imagem' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=600',
            ],
            12 => [
                'id' => 12,
                'nome' => 'Passeio de Escuna pelas Ilhas',
                'cidade' => 'Florianópolis',
                'descricao' => 'Navegação pela baía de Florianópolis, passando por ilhas paradisíacas com paradas para mergulho.',
                'preco' => 400.00,
                'imagem' => 'https://picsum.photos/seed/escuna/600/400',
            ],
            13 => [
                'id' => 13,
                'nome' => 'Jantar Romântico à Beira-Mar',
                'cidade' => 'Florianópolis',
                'descricao' => 'Noite especial em restaurante exclusivo à beira-mar com menu degustação e vista para o pôr do sol.',
                'preco' => 521.00,
                'imagem' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600',
            ],
            14 => [
                'id' => 14,
                'nome' => 'Voo de Parapente em Floripa',
                'cidade' => 'Florianópolis',
                'descricao' => 'Voo duplo de parapente na Praia Mole com vista aérea incrível da costa catarinense e fotos profissionais.',
                'preco' => 450.00,
                'imagem' => 'https://images.unsplash.com/photo-1503256207526-0d5d80fa2f47?w=600',
            ],
            15 => [
                'id' => 15,
                'nome' => 'Suite Romântica + Café da Manhã',
                'cidade' => 'Florianópolis',
                'descricao' => 'Uma noite em suíte premium com decoração romântica, espumante, jacuzzi e café da manhã especial.',
                'preco' => 900.00,
                'imagem' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600',
            ],
            // 3 presentes de 150
            16 => [
                'id' => 16,
                'nome' => 'Café Colonial Rústico',
                'cidade' => 'Curitiba',
                'descricao' => 'Autêntico café colonial curitibano com dezenas de opções de doces, bolos, tortas e salgados artesanais.',
                'preco' => 150.00,
                'imagem' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=600',
            ],
            17 => [
                'id' => 17,
                'nome' => 'Stand Up Paddle ao Pôr do Sol',
                'cidade' => 'Balneário Camboriú',
                'descricao' => 'Aluguel de pranchas de SUP e instrução básica para curtirmos o pôr do sol no mar tranquílo.',
                'preco' => 150.00,
                'imagem' => 'https://picsum.photos/seed/paddle/600/400',
            ],
            18 => [
                'id' => 18,
                'nome' => 'Snorkel nas Águas Cristalinas',
                'cidade' => 'Florianópolis',
                'descricao' => 'Experiência de mergulho livre (snorkel) observando a vida marinha nas piscinas naturais.',
                'preco' => 150.00,
                'imagem' => 'https://picsum.photos/seed/snorkel/600/400',
            ],
            // 3 presentes de 175
            19 => [
                'id' => 19,
                'nome' => 'Bosque Alemão e Parques',
                'cidade' => 'Curitiba',
                'descricao' => 'Passeio fotográfico guiado pelos parques mais icônicos, incluindo o Bosque Alemão e Parque Tanguá.',
                'preco' => 175.00,
                'imagem' => 'https://picsum.photos/seed/bosque/600/400',
            ],
            20 => [
                'id' => 20,
                'nome' => 'Passeio no Oceanic Aquarium',
                'cidade' => 'Balneário Camboriú',
                'descricao' => 'Ingressos casal para o imenso aquário local, conhecendo diversas espécies marinhas e atrações.',
                'preco' => 175.00,
                'imagem' => 'https://images.unsplash.com/photo-1582967788606-a171c1080cb0?w=600',
            ],
            21 => [
                'id' => 21,
                'nome' => 'Aventura de Quadriciclo',
                'cidade' => 'Florianópolis',
                'descricao' => 'Aluguel de quadriciclo para uma aventura emocionante nas trilhas costeiras da ilha da magia.',
                'preco' => 175.00,
                'imagem' => 'https://picsum.photos/seed/quadriciclo/600/400',
            ],
            // 3 presentes de 220
            22 => [
                'id' => 22,
                'nome' => 'Almoço em Santa Felicidade',
                'cidade' => 'Curitiba',
                'descricao' => 'Tradicionalíssimo almoço italiano farto no polo gastronômico de Santa Felicidade.',
                'preco' => 220.00,
                'imagem' => 'https://picsum.photos/seed/almoco/600/400',
            ],
            23 => [
                'id' => 23,
                'nome' => 'Jantar Temático Surpresa',
                'cidade' => 'Balneário Camboriú',
                'descricao' => 'Uma experiência noturna super divertida e saborosa num dos restaurantes temáticos da orla.',
                'preco' => 220.00,
                'imagem' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=600',
            ],
            24 => [
                'id' => 24,
                'nome' => 'Passeio Ilha do Campeche',
                'cidade' => 'Florianópolis',
                'descricao' => 'Translado e acesso à paradisíaca Ilha do Campeche, também conhecida como o Caribe brasileiro.',
                'preco' => 220.00,
                'imagem' => 'https://picsum.photos/seed/campeche/600/400',
            ],
            // 3 presentes de 225
            25 => [
                'id' => 25,
                'nome' => 'Degustação de Vinhos',
                'cidade' => 'Curitiba',
                'descricao' => 'Excursão próxima a Curitiba com degustação dos melhores vinhos da região serrana.',
                'preco' => 225.00,
                'imagem' => 'https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?w=600',
            ],
            26 => [
                'id' => 26,
                'nome' => 'Trilha Guiada + Piquenique',
                'cidade' => 'Balneário Camboriú',
                'descricao' => 'Caminhada cercada pela natureza local que termina em um delicioso e caprichado piquenique a dois.',
                'preco' => 225.00,
                'imagem' => 'https://picsum.photos/seed/trilha/600/400',
            ],
            27 => [
                'id' => 27,
                'nome' => 'Aulas Iniciais de Surf',
                'cidade' => 'Florianópolis',
                'descricao' => 'Aulas de surf no melhor pico da ilha, com equipamento incluso e instrutor profissional.',
                'preco' => 225.00,
                'imagem' => 'https://images.unsplash.com/photo-1502680390469-be75c86b636f?w=600',
            ],
            // 3 presentes de 300
            28 => [
                'id' => 28,
                'nome' => 'Noite no Hard Rock Cafe',
                'cidade' => 'Curitiba',
                'descricao' => 'Uma noite super animada regada a drinks e lanches incríveis com rock clássico de fundo.',
                'preco' => 300.00,
                'imagem' => 'https://images.unsplash.com/photo-1514933651103-005eec06c04b?w=600',
            ],
            29 => [
                'id' => 29,
                'nome' => 'Passeio de Lancha Privativa',
                'cidade' => 'Balneário Camboriú',
                'descricao' => 'Breve passeio de lancha pelo litoral catarinense num modelo confortável só para o casal.',
                'preco' => 300.00,
                'imagem' => 'https://picsum.photos/seed/lancha/600/400',
            ],
            30 => [
                'id' => 30,
                'nome' => 'Day Use Beach Club Jurerê',
                'cidade' => 'Florianópolis',
                'descricao' => 'Muita mordomia e conforto em um Day Use fantástico em um dos points mais balados.',
                'preco' => 300.00,
                'imagem' => 'https://picsum.photos/seed/jurere/600/400',
            ],
            // 3 presentes de 350
            31 => [
                'id' => 31,
                'nome' => 'Ensaio Fotográfico Pós Wedding',
                'cidade' => 'Curitiba',
                'descricao' => 'Sessão fotográfica profissional com nossos trajes e cenários românticos pra guardar pra sempre.',
                'preco' => 350.00,
                'imagem' => 'https://picsum.photos/seed/ensaio/600/400',
            ],
            32 => [
                'id' => 32,
                'nome' => 'Parque Beto Carrero World',
                'cidade' => 'Balneário Camboriú',
                'descricao' => 'Ingressos do casal para um dia inteirinho e mágico e cheio de adrenalina no parque.',
                'preco' => 350.00,
                'imagem' => 'https://picsum.photos/seed/betocarrero/600/400',
            ],
            33 => [
                'id' => 33,
                'nome' => 'Jantar Romântico de Frutos do Mar',
                'cidade' => 'Florianópolis',
                'descricao' => 'Banquete especial pra quem ama a gastronomia litorânea com a pesca mais fresca da região.',
                'preco' => 350.00,
                'imagem' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=600',
            ],
        ];
    }

    /**
     * IDs dos presentes já comprados
     */
    public static function getComprados()
    {
        return PresenteComprado::pluck('presente_id')->toArray();
    }

    /**
     * Mostra a tela de pagamento de um presente
     */
    public function show($id)
    {
        $id = (int) $id;
        $presentes = self::getPresentes();

        if (!isset($presentes[$id])) {
            return redirect('/')->with('error', 'Presente não encontrado.');
        }

        // Verificar se já foi comprado
        $jaComprado = PresenteComprado::where('presente_id', $id)->exists();
        if ($jaComprado) {
            return redirect('/#presentes')->with('error', 'Este presente já foi escolhido por outro convidado.');
        }

        $presente = $presentes[$id];

        return view('presentes.show', compact('presente'));
    }

    /**
     * Upload de comprovante e registro da compra
     */
    public function uploadComprovante(Request $request, $id)
    {
        $request->validate([
            'comprovante' => 'required|file|mimes:jpg,jpeg,png,pdf,webp|max:10240',
            'metodo_pagamento' => 'required|in:pix,cartao',
        ]);

        $id = (int) $id;
        $presentes = self::getPresentes();

        if (!isset($presentes[$id])) {
            return response()->json(['success' => false, 'message' => 'Presente não encontrado.'], 404);
        }

        // Verificar se já foi comprado
        $jaComprado = PresenteComprado::where('presente_id', $id)->exists();
        if ($jaComprado) {
            return response()->json(['success' => false, 'message' => 'Este presente já foi escolhido por outro convidado.'], 409);
        }

        $presente = $presentes[$id];
        $user = Auth::user();

        // Salvar comprovante
        $fileName = 'comprovante_' . $user->id . '_presente_' . $id . '_' . time() . '.' . $request->file('comprovante')->getClientOriginalExtension();
        $path = $request->file('comprovante')->storeAs('comprovantes', $fileName, 'public');

        // Registrar compra
        PresenteComprado::create([
            'presente_id' => $id,
            'user_id' => $user->id,
            'metodo_pagamento' => $request->input('metodo_pagamento'),
            'comprovante_path' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comprovante enviado com sucesso! Obrigado pelo presente: ' . $presente['nome'],
            'path' => $path,
        ]);
    }

    /**
     * Mostra o histórico de presentes comprados do usuário logado
     */
    public function meusPresentes()
    {
        $user = Auth::user();
        $compras = PresenteComprado::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $presentesDetalhes = self::getPresentes();
        
        $meusPresentes = [];
        foreach ($compras as $compra) {
            if (isset($presentesDetalhes[$compra->presente_id])) {
                $detalhe = $presentesDetalhes[$compra->presente_id];
                $meusPresentes[] = [
                    'id' => $compra->id,
                    'presente_id' => $detalhe['id'],
                    'nome' => $detalhe['nome'],
                    'cidade' => $detalhe['cidade'],
                    'preco' => $detalhe['preco'],
                    'imagem' => $detalhe['imagem'],
                    'data_compra' => $compra->created_at,
                    'status' => 'Confirmado',
                    'metodo' => $compra->metodo_pagamento
                ];
            }
        }

        return view('presentes.meus-presentes', compact('meusPresentes'));
    }
}
