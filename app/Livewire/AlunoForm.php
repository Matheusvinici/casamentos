<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Aluno;
use App\Models\Escola;
use App\Models\Bairro;
use App\Models\Cidade;
use App\Models\Pais;
use App\Models\Deficiencia;
use Illuminate\Support\Facades\Validator;


class AlunoForm extends Component
{
    public $alunoId = null;
    public $nome = '';
    public $telefone = '';
    public $email = '';
    public $endereco = '';
    public $bairro_id = null;
    public $cidade_id = null;
    public $pais_id = null;
    public $distrito = '';
    public $turno_escola = '';
    public $turno_idioma = '';
    public $contato_emergencia = '';
    public $escola_id = null;
    public $escola_estado = '';
    public $data_nascimento = '';
    public $tipo = '';
    public $origem = '';
    public $origem_servidor = '';
    public $responsavel_nome = '';
    public $responsavel_telefone = '';
    public $responsavel_cpf = '';
    public $responsavel_email = '';
    public $responsavel_endereco = '';
    public $ano_escolar = '';      
    public $aluno_cpf = ''; 
    public $raca_cor = '';         
    public $possui_deficiencia = 'nao';  
    public $deficiencias_selecionadas = [];  
    public $todas_deficiencias = [];  
       

    public $escolas = [];
    public $bairros = [];
    public $cidades = [];
    public $paises = [];

    protected $rules = [
        'nome' => 'required|string|max:255',
        'telefone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'endereco' => 'nullable|string|max:255',
        'bairro_id' => 'nullable|exists:bairros,id',
        'cidade_id' => 'nullable|exists:cidades,id',
        'pais_id' => 'nullable|exists:paises,id',
        'distrito' => 'nullable|string|max:255',
        'turno_idioma' => 'nullable|string|max:255',
        'contato_emergencia' => 'nullable|string|max:20',
        'data_nascimento' => 'required|date',
        'tipo' => 'required|in:aluno_rede,aluno_estado,servidor,outros',
        'escola_id' => 'nullable|exists:escolas,id',
        'escola_estado' => 'nullable|string|max:255',
        'turno_escola' => 'nullable|string|max:255',
        'origem' => 'nullable|in:municipal,estadual',
        'origem_servidor' => 'nullable|string|max:255',
        'responsavel_nome' => 'nullable|string|max:255',
        'responsavel_telefone' => 'nullable|string|max:20',
        'responsavel_cpf' => 'nullable|string|max:14',
        'responsavel_email' => 'nullable|email|max:255',
        'responsavel_endereco' => 'nullable|string|max:255',
        'ano_escolar' => 'nullable|string|max:50',     
        'aluno_cpf' => 'nullable|string|max:14',
        'ano_escolar' => 'nullable|string|max:50',     
        'raca_cor' => 'nullable|string|max:50',  // NOVA REGRA
        'deficiencias_selecionadas' => 'nullable|array',  // NOVA REGRA
        'deficiencias_selecionadas.*' => 'exists:deficiencias,id',  // NOVA REGRA
    ];

    protected $validationAttributes = [
        'nome' => 'nome',
        'telefone' => 'telefone',
        'email' => 'email',
        'endereco' => 'endereço',
        'bairro_id' => 'bairro',
        'cidade_id' => 'cidade',
        'pais_id' => 'país',
        'distrito' => 'distrito',
        'turno_escola' => 'turno na escola de origem',
        'turno_idioma' => 'turno na escola de idiomas',
        'contato_emergencia' => 'contato de emergência',
        'escola_id' => 'escola municipal',
        'escola_estado' => 'escola estadual',
        'data_nascimento' => 'data de nascimento',
        'tipo' => 'tipo',
        'origem' => 'origem',
        'origem_servidor' => 'secretaria de origem',
        'responsavel_nome' => 'nome do responsável',
        'responsavel_telefone' => 'telefone do responsável',
        'responsavel_cpf' => 'CPF do responsável',
        'responsavel_email' => 'email do responsável',
        'responsavel_endereco' => 'endereço do responsável',
        'responsavel_endereco' => 'endereço do responsável',
        'raca_cor' => 'raça/cor',  // NOVO
        'deficiencias_selecionadas' => 'deficiências',  // NOVO
    ];

    protected $listeners = [
        'updateSelected' => 'handleUpdateSelected',
    ];
       protected function carregarDeficiencias(): void
{
    $this->todas_deficiencias = Deficiencia::where('ativo', true)
        ->orderBy('nome')
        ->get();
    
    
    
    if ($this->todas_deficiencias->count() > 0) {
        foreach ($this->todas_deficiencias as $deficiencia) {
           
        }
    } else {
       
    }
}

    public function mount($alunoId = null)
    {
        $this->loadData();
        $this->carregarDeficiencias();  // NOVO

        if ($alunoId) {
            $this->fillAluno($alunoId);
        }
    }

    protected function loadData(): void
    {
        $this->escolas = Escola::all();
        $this->bairros = Bairro::all();
        $this->cidades = Cidade::all();
        $this->paises = Pais::all();
    }

    protected function fillAluno($alunoId): void
    {
         $aluno = Aluno::with('deficiencias')->findOrFail($alunoId);
        $this->alunoId = $aluno->id;
        $this->nome = $aluno->nome;
        $this->telefone = $aluno->telefone;
        $this->email = $aluno->email;
        $this->endereco = $aluno->endereco;
        $this->bairro_id = $aluno->bairro_id;
        $this->cidade_id = $aluno->cidade_id;
        $this->pais_id = $aluno->pais_id;
        $this->distrito = $aluno->distrito;
        $this->turno_escola = $aluno->turno_escola;
        $this->turno_idioma = $aluno->turno_idioma;
        $this->contato_emergencia = $aluno->contato_emergencia;
        $this->escola_id = $aluno->escola_id;
        $this->escola_estado = $aluno->escola_estado ?? '';
        $this->data_nascimento = $aluno->data_nascimento ? $aluno->data_nascimento->format('Y-m-d') : null;
        $this->tipo = $aluno->tipo;
        $this->origem = $aluno->origem ?? '';
        $this->origem_servidor = $aluno->origem_servidor ?? '';
        $this->responsavel_nome = $aluno->responsavel_nome;
        $this->responsavel_telefone = $aluno->responsavel_telefone;
        $this->responsavel_cpf = $aluno->responsavel_cpf;
        $this->responsavel_email = $aluno->responsavel_email;
        $this->responsavel_endereco = $aluno->responsavel_endereco;
          $this->ano_escolar = $aluno->ano_escolar ?? '';      
         $this->aluno_cpf = $aluno->aluno_cpf ?? '';
         $this->raca_cor = $aluno->raca_cor ?? '';
           if ($aluno->deficiencias->count() > 0) {
            $this->possui_deficiencia = 'sim';
            $this->deficiencias_selecionadas = $aluno->deficiencias->pluck('id')->toArray();
        } else {
            $this->possui_deficiencia = 'nao';
            $this->deficiencias_selecionadas = [];
        }          
    }
       public function updatedPossuiDeficiencia($value)
{
   
    
    if ($value === 'nao') {
        $this->deficiencias_selecionadas = [];
    }
    
    // Força a re-renderização
    $this->dispatch('$refresh');
}

    public function handleUpdateSelected($type, $id): void
    {
        $property = $type . '_id';
        if (property_exists($this, $property)) {
            $this->$property = $id;
        }
    }

    public function updateTipo(): void
    {
        if ($this->tipo === 'aluno_rede') {
            $this->origem = 'municipal';
            $this->escola_id = null;
            $this->escola_estado = '';
        } elseif ($this->tipo === 'aluno_estado') {
            $this->origem = 'estadual';
            $this->escola_id = null;
            $this->escola_estado = '';
        } elseif ($this->tipo === 'servidor' || $this->tipo === 'outros') {
            $this->escola_id = null;
            $this->escola_estado = '';
            $this->turno_escola = '';
            $this->origem = '';
            $this->responsavel_nome = '';
            $this->responsavel_telefone = '';
            $this->responsavel_cpf = '';
            $this->responsavel_email = '';
            $this->responsavel_endereco = '';
        }
    }

    public function save()
    {
        try {
            $rules = $this->rules;

            // Ajuste das regras com base no tipo
            if ($this->tipo === 'aluno_rede') {
                $rules['escola_id'] = 'required|exists:escolas,id';
                $rules['escola_estado'] = 'nullable|string|max:255';
            } elseif ($this->tipo === 'aluno_estado') {
                $rules['escola_estado'] = 'required|string|max:255';
                unset($rules['escola_id']);
            } elseif ($this->tipo === 'servidor' || $this->tipo === 'outros') {
                $rules['origem_servidor'] = 'required|string|max:255';
            }

           
            $validator = Validator::make($this->all(), $rules, [], $this->validationAttributes);
            if ($validator->fails()) {
                $this->setErrorBag($validator->errors());
                
                return;
            }

            $dataToSave = [
                'nome' => $this->nome,
                'telefone' => $this->telefone,
                'email' => $this->email,
                'endereco' => $this->endereco,
                'bairro_id' => $this->bairro_id,
                'cidade_id' => $this->cidade_id,
                'pais_id' => $this->pais_id,
                'distrito' => $this->distrito,
                'turno_idioma' => $this->turno_idioma,
                'contato_emergencia' => $this->contato_emergencia,
                'data_nascimento' => $this->data_nascimento,
                'tipo' => $this->tipo,
                'ano_escolar' => $this->ano_escolar,           
                'aluno_cpf' => $this->aluno_cpf,
                'raca_cor' => $this->raca_cor,  // ADICIONADO
            ];

            if ($this->tipo === 'aluno_rede') {
                $dataToSave['escola_id'] = $this->escola_id;
                $dataToSave['escola_estado'] = null;
                $dataToSave['turno_escola'] = $this->turno_escola;
                $dataToSave['origem'] = 'municipal';
                $dataToSave['responsavel_nome'] = $this->responsavel_nome;
                $dataToSave['responsavel_telefone'] = $this->responsavel_telefone;
                $dataToSave['responsavel_cpf'] = $this->responsavel_cpf;
                $dataToSave['responsavel_email'] = $this->responsavel_email;
                $dataToSave['responsavel_endereco'] = $this->responsavel_endereco;
                $dataToSave['origem_servidor'] = null;
            } elseif ($this->tipo === 'aluno_estado') {
                $dataToSave['escola_id'] = null;
                $dataToSave['escola_estado'] = $this->escola_estado;
                $dataToSave['turno_escola'] = $this->turno_escola;
                $dataToSave['origem'] = 'estadual';
                $dataToSave['responsavel_nome'] = $this->responsavel_nome;
                $dataToSave['responsavel_telefone'] = $this->responsavel_telefone;
                $dataToSave['responsavel_cpf'] = $this->responsavel_cpf;
                $dataToSave['responsavel_email'] = $this->responsavel_email;
                $dataToSave['responsavel_endereco'] = $this->responsavel_endereco;
                $dataToSave['origem_servidor'] = null;
            } else {
                $dataToSave['origem_servidor'] = $this->origem_servidor;
                $dataToSave['escola_id'] = null;
                $dataToSave['escola_estado'] = null;
                $dataToSave['turno_escola'] = null;
                $dataToSave['origem'] = null;
                $dataToSave['responsavel_nome'] = null;
                $dataToSave['responsavel_telefone'] = null;
                $dataToSave['responsavel_cpf'] = null;
                $dataToSave['responsavel_email'] = null;
                $dataToSave['responsavel_endereco'] = null;
            }

           
            
            if ($this->alunoId) {
                $aluno = Aluno::findOrFail($this->alunoId);
                $aluno->update($dataToSave);
                
                // Atualizar deficiências - ADICIONADO
                if ($this->possui_deficiencia === 'sim') {
                    $aluno->deficiencias()->sync($this->deficiencias_selecionadas);
                } else {
                    $aluno->deficiencias()->sync([]);
                }
                
                session()->flash('success', 'Aluno atualizado com sucesso!');
            } else {
                $aluno = Aluno::create($dataToSave);
                
                // Salvar deficiências - ADICIONADO
                if ($this->possui_deficiencia === 'sim' && !empty($this->deficiencias_selecionadas)) {
                    $aluno->deficiencias()->attach($this->deficiencias_selecionadas);
                }
                
                session()->flash('success', 'Aluno registrado com sucesso!');
            }

            
            return redirect()->route('Listar-Alunos');

        } catch (\Exception $e) {
            
            session()->flash('error', 'Erro ao salvar o aluno: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function render()
{
  
    
    return view('livewire.aluno-form', [
        'aluno_id' => $this->alunoId,
        'escola_id' => $this->escola_id,
        'cidade_id' => $this->cidade_id,
        'bairro_id' => $this->bairro_id,
        'pais_id' => $this->pais_id,
    ])->layout('layouts.app');
}
}