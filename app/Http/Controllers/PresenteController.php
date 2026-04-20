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
                'imagem' => 'https://loremflickr.com/600/400/cablecar,view/all',
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
                'imagem' => 'https://images.unsplash.com/photo-1657012222671-5d5dc8a57f37?w=600',
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
                'imagem' => 'https://loremflickr.com/600/400/paragliding,sky/all',
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
                'imagem' => 'https://images.unsplash.com/photo-1520116468816-95b69f847357?w=600',
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
                'imagem' => 'https://images.unsplash.com/photo-1496521061024-90e1c1221555?w=600',
            ],
            // 3 presentes de 220
            22 => [
                'id' => 22,
                'nome' => 'Almoço em Santa Felicidade',
                'cidade' => 'Curitiba',
                'descricao' => 'Tradicionalíssimo almoço italiano farto no polo gastronômico de Santa Felicidade.',
                'preco' => 220.00,
                'imagem' => 'https://loremflickr.com/600/400/pasta,italian/all',
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
                'imagem' => 'https://loremflickr.com/600/400/tropical,island/all',
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
                'imagem' => 'https://loremflickr.com/600/400/speedboat,ocean/all',
            ],
            30 => [
                'id' => 30,
                'nome' => 'Day Use Beach Club Jurerê',
                'cidade' => 'Florianópolis',
                'descricao' => 'Muita mordomia e conforto em um Day Use fantástico em um dos points mais balados.',
                'preco' => 300.00,
                'imagem' => 'https://loremflickr.com/600/400/beachclub,pool/all',
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
                'imagem' => 'https://loremflickr.com/600/400/rollercoaster,park/all',
            ],
            33 => [
                'id' => 33,
                'nome' => 'Jantar Romântico de Frutos do Mar',
                'cidade' => 'Florianópolis',
                'descricao' => 'Banquete especial pra quem ama a gastronomia litorânea com a pesca mais fresca da região.',
                'preco' => 350.00,
                'imagem' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=600',
            ],
            // 15 novos presentes de restaurantes (R$ 150 a R$ 250)
            // Curitiba
            34 => [
                'id' => 34,
                'nome' => 'Jantar Italiano em Santa Felicidade',
                'cidade' => 'Curitiba',
                'descricao' => 'Banquete italiano completo num dos mais tradicionais restaurantes gastronômicos.',
                'preco' => 180.00,
                'imagem' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600',
            ],
            35 => [
                'id' => 35,
                'nome' => 'Fondue Casal Curitiba',
                'cidade' => 'Curitiba',
                'descricao' => 'Sequência completa de fondue (queijo, carne e chocolate) para espantar o frio com muito romance.',
                'preco' => 250.00,
                'imagem' => 'https://images.unsplash.com/photo-1630257574313-9bacc3c521d8?w=600',
            ],
            36 => [
                'id' => 36,
                'nome' => 'Almoço no Largo da Ordem',
                'cidade' => 'Curitiba',
                'descricao' => 'Almoço descontraído acompanhado de chopp artesanal no histórico Largo da Ordem.',
                'preco' => 150.00,
                'imagem' => 'https://loremflickr.com/600/400/brazilian,food/all',
            ],
            37 => [
                'id' => 37,
                'nome' => 'Jantar Árabe Especial',
                'cidade' => 'Curitiba',
                'descricao' => 'Uma viagem gastronômica ao Oriente Médio com farto banquete árabe de degustação.',
                'preco' => 200.00,
                'imagem' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=600',
            ],
            38 => [
                'id' => 38,
                'nome' => 'Pizza Premium em Curitiba',
                'cidade' => 'Curitiba',
                'descricao' => 'Noite da pizza em local super aconchegante com as melhores redondas ao estilo napolitano.',
                'preco' => 160.00,
                'imagem' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=600',
            ],
            // Balneário Camboriú
            39 => [
                'id' => 39,
                'nome' => 'Jantar com Vista Mar em BC',
                'cidade' => 'Balneário Camboriú',
                'descricao' => 'Restaurante panorâmico na orla com pratos contemporâneos para o casal.',
                'preco' => 240.00,
                'imagem' => 'https://images.unsplash.com/photo-1772352214475-12f9a75618d8?w=600',
            ],
            40 => [
                'id' => 40,
                'nome' => 'Almoço Pescados em BC',
                'cidade' => 'Balneário Camboriú',
                'descricao' => 'Generosa porção de pescados fritos num bistrô com clima super tropical.',
                'preco' => 210.00,
                'imagem' => 'https://loremflickr.com/600/400/fish,dish/all',
            ],
            41 => [
                'id' => 41,
                'nome' => 'Rodízio de Carnes Premium',
                'cidade' => 'Balneário Camboriú',
                'descricao' => 'Clássica churrascaria de BC para celebrar com cortes nobres e buffet livre.',
                'preco' => 250.00,
                'imagem' => 'https://loremflickr.com/600/400/barbecue,meat/all',
            ],
            42 => [
                'id' => 42,
                'nome' => 'Hambúrguer Artesanal do Casal',
                'cidade' => 'Balneário Camboriú',
                'descricao' => 'Combos completões nas melhores hamburguerias temáticas espalhadas por BC.',
                'preco' => 150.00,
                'imagem' => 'https://loremflickr.com/600/400/burger,fries/all',
            ],
            43 => [
                'id' => 43,
                'nome' => 'Noite Mexicana em BC',
                'cidade' => 'Balneário Camboriú',
                'descricao' => 'Muita animação, tacos, burritos e margaritas numa das famosas casas mexicanas.',
                'preco' => 180.00,
                'imagem' => 'https://loremflickr.com/600/400/tacos,mexican/all',
            ],
            // Florianópolis
            44 => [
                'id' => 44,
                'nome' => 'Sequência de Camarão na Lagoa',
                'cidade' => 'Florianópolis',
                'descricao' => 'Tradicional e farta sequência de camarão à beira da maravilhosa Lagoa da Conceição.',
                'preco' => 250.00,
                'imagem' => 'https://loremflickr.com/600/400/shrimp,seafood/all',
            ],
            45 => [
                'id' => 45,
                'nome' => 'Jantar Açoriano Tradicional',
                'cidade' => 'Florianópolis',
                'descricao' => 'Saboreando as raízes de Floripa em um autêntico restaurante com receitas coloniais.',
                'preco' => 220.00,
                'imagem' => 'https://loremflickr.com/600/400/stew,seafood/all',
            ],
            46 => [
                'id' => 46,
                'nome' => 'Ostras e Frutos do Mar',
                'cidade' => 'Florianópolis',
                'descricao' => 'Experiência gastronômica incrível em Santo Antônio de Lisboa saboreando as melhores ostras.',
                'preco' => 230.00,
                'imagem' => 'https://images.unsplash.com/photo-1578882422378-9ed72be08b5e?w=600',
            ],
            47 => [
                'id' => 47,
                'nome' => 'Almoço Beira-Mar Praia Mole',
                'cidade' => 'Florianópolis',
                'descricao' => 'Menu praiano com os pés na areia badalada da Praia Mole.',
                'preco' => 190.00,
                'imagem' => 'https://loremflickr.com/600/400/beach,restaurant/all',
            ],
            48 => [
                'id' => 48,
                'nome' => 'Jantar de Massas na Ilha',
                'cidade' => 'Florianópolis',
                'descricao' => 'Uma cantina charmosa e escondida no centrinho da Lagoa com o melhor da culinária italiana.',
                'preco' => 170.00,
                'imagem' => 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=600',
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

    public function bloquear(Request $request, $id)
    {
        $id = (int) $id;
        $presentes = self::getPresentes();

        if (!isset($presentes[$id])) {
            return response()->json(['success' => false, 'message' => 'Presente não encontrado.'], 404);
        }

        $jaComprado = PresenteComprado::where('presente_id', $id)->first();
        if ($jaComprado && $jaComprado->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Este presente já foi escolhido.'], 409);
        }

        if (!$jaComprado) {
            PresenteComprado::create([
                'presente_id' => $id,
                'user_id' => Auth::id(),
                'metodo_pagamento' => 'pendente',
                'comprovante_path' => null,
            ]);
        }

        return response()->json(['success' => true]);
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

        // Verificar se já foi comprado por outra pessoa
        $jaComprado = PresenteComprado::where('presente_id', $id)->first();
        if ($jaComprado && $jaComprado->user_id !== Auth::id()) {
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

        // Verificar se já foi comprado por outro
        $jaComprado = PresenteComprado::where('presente_id', $id)->first();
        if ($jaComprado && $jaComprado->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Este presente já foi escolhido por outro convidado.'], 409);
        }

        $presente = $presentes[$id];
        $user = Auth::user();

        // Salvar comprovante
        $fileName = 'comprovante_' . $user->id . '_presente_' . $id . '_' . time() . '.' . $request->file('comprovante')->getClientOriginalExtension();
        $path = $request->file('comprovante')->storeAs('comprovantes', $fileName, 'public');

        // Registrar compra ou atualizar caso já tenha sido bloqueado
        if ($jaComprado) {
            $jaComprado->update([
                'metodo_pagamento' => $request->input('metodo_pagamento'),
                'comprovante_path' => $path,
            ]);
        } else {
            PresenteComprado::create([
                'presente_id' => $id,
                'user_id' => $user->id,
                'metodo_pagamento' => $request->input('metodo_pagamento'),
                'comprovante_path' => $path,
            ]);
        }

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
