<div class="card">
    <div class="card-header">
        <div class="page-title">
            <div class="page-title-wrapper">
                <div class="page-heading">
                    <div>
                        <h5 class="m-0 text-dark">{{ $alunoId ? 'Editar Aluno' : 'Criar Novo Aluno' }}</h5>
                        <p class="text-muted">Gerencie os dados do aluno no sistema</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="container-fluid py-3 py-md-4">
        <div class="card shadow-xl rounded-1 mx-2 mb-3">
            <div class="card-header text-muted py-3 card-border">
                <h4 class="h4 mb-1 fw-semibold">Formulário de Cadastro</h4>
            </div>
            <div class="card-body p-4">
                <form wire:submit="save">
                    <div class="row">
                        <!-- Left Column: Informações da Escola e Informações da Escola de Idiomas -->
                        <div class="col-md-6">
                            <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">Informações da Escola</h5>
                            <div class="form-group mb-3">
                                <label for="tipo">Tipo</label>
                                <select class="form-control" id="tipo" wire:model="tipo" wire:change="updateTipo" required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="aluno_rede">Aluno Rede</option>
                                    <option value="aluno_estado">Aluno Estado</option>
                                    <option value="servidor">Servidor</option>
                                    <option value="outros">Outros</option>
                                </select>
                            </div>
                            @if($tipo === 'aluno_rede' || $tipo === 'aluno_estado')
                            @if($tipo === 'aluno_rede')
                            <div class="form-group mb-3">
                                <label for="escola_id">Escola Municipal</label>
                                <livewire:search-select type="escola" :selectedId="$escola_id" />
                            </div>
                            @endif
                            @if($tipo === 'aluno_estado')
                            <div class="form-group mb-3">
                                <label for="escola_estado">Escola Estadual</label>
                                <input type="text" class="form-control" id="escola_estado" wire:model="escola_estado" placeholder="Digite o nome da escola estadual">
                            </div>
                            @endif
                            <div class="form-group mb-3">
                                <label for="turno_escola">Turno de Estudo na Escola de Origem</label>
                                <select class="form-control" id="turno_escola" wire:model="turno_escola">
                                    <option value="" disabled selected>Selecione o turno</option>
                                    <option value="Matutino">Matutino</option>
                                    <option value="Vespertino">Vespertino</option>
                                    <option value="Integral">Integral</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="origem">Origem</label>
                                <select class="form-control" id="origem" wire:model="origem" disabled>
                                    @if($tipo === 'aluno_rede')
                                        <option value="municipal" {{ $origem === 'municipal' ? 'selected' : '' }}>Municipal</option>
                                    @elseif($tipo === 'aluno_estado')
                                        <option value="estadual" {{ $origem === 'estadual' ? 'selected' : '' }}>Estadual</option>
                                    @endif
                                </select>
                            </div>
                            <!-- Seção de Informações do Responsável (simples e direta) -->
                            <div class="form-group mb-3">
                                <label for="responsavel_nome">Nome do Responsável</label>
                                <input type="text" class="form-control" id="responsavel_nome" wire:model="responsavel_nome" placeholder="Digite o nome completo">
                            </div>
                            <div class="form-group mb-3">
                                <label for="responsavel_telefone">Telefone do Responsável</label>
                                <input type="text" class="form-control" id="responsavel_telefone" wire:model="responsavel_telefone" placeholder="Ex: (12) 3456-7890">
                            </div>
                            <div class="form-group mb-3">
                                <label for="responsavel_cpf">CPF do Responsável</label>
                                <input type="text" class="form-control" id="responsavel_cpf" wire:model="responsavel_cpf" placeholder="Ex: 123.456.789-00">
                            </div>
                            <div class="form-group mb-3">
                                <label for="responsavel_email">Email do Responsável</label>
                                <input type="email" class="form-control" id="responsavel_email" wire:model="responsavel_email" placeholder="Ex: responsavel@exemplo.com">
                            </div>
                            <div class="form-group mb-3">
                                <label for="responsavel_endereco">Endereço do Responsável</label>
                                <input type="text" class="form-control" id="responsavel_endereco" wire:model="responsavel_endereco" placeholder="Ex: Rua das Flores, 123">
                            </div>
                            @endif
                            @if($tipo === 'servidor' || $tipo === 'outros')
                            <div class="form-group mb-3">
                                <label for="origem_servidor">Secretaria de Origem</label>
                                <select class="form-control" id="origem_servidor" wire:model="origem_servidor">
                                    <option value="" selected>Selecione a secretaria</option>
                                    <option value="Secretaria de Educação">Secretaria de Educação</option>
                                    <option value="Secretaria de Cultura">Secretaria de Cultura</option>
                                    <option value="Secretaria de Meio Ambiente">Secretaria de Meio Ambiente</option>
                                    <option value="Secretaria de Serviços Públicos">Secretaria de Serviços Públicos</option>
                                    <option value="Secretaria de Desenvolvimento Social (Sedes)">Secretaria de Desenvolvimento Social (Sedes)</option>
                                </select>
                            </div>
                            @endif

                            <h5 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-3">Informações da Escola de Idiomas</h5>
                            <div class="form-group mb-3">
                                <label for="turno_idioma">Turno de Estudo na Escola de Idiomas</label>
                                <select class="form-control" id="turno_idioma" wire:model="turno_idioma">
                                    <option value="" disabled selected>Selecione o turno</option>
                                    <option value="Matutino">Matutino</option>
                                    <option value="Vespertino">Vespertino</option>
                                    <option value="Integral">Integral</option>
                                </select>
                            </div>
                        </div>

                        <!-- Right Column: Informações Pessoais e Informações de Endereço -->
                        <div class="col-md-6">
                            <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">Informações Pessoais</h5>
                            <div class="form-group mb-3">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control" id="nome" wire:model="nome" placeholder="Digite o nome completo" required>
                            </div>
<!-- Possui Deficiência? -->
<div class="form-group mb-3">
    <label class="form-label d-block">Possui Deficiência?</label>
    <div class="d-flex gap-3">
        <div class="form-check">
            <input class="form-check-input" type="radio" wire:model.live="possui_deficiencia" value="nao" id="deficiencia_nao">
            <label class="form-check-label" for="deficiencia_nao">
                Não possui
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" wire:model.live="possui_deficiencia" value="sim" id="deficiencia_sim">
            <label class="form-check-label" for="deficiencia_sim">
                Possui
            </label>
        </div>
    </div>
</div>

<!-- Lista de Deficiências (aparece apenas se possui_deficiencia = sim) -->
<div wire:key="deficiencias-{{ $possui_deficiencia }}">
    @if($possui_deficiencia === 'sim')
        <div class="form-group mb-3">
            <label class="form-label">Selecione as Deficiências</label>
            
            @if(count($todas_deficiencias) > 0)
                <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                    @foreach($todas_deficiencias as $deficiencia)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   wire:model="deficiencias_selecionadas" 
                                   value="{{ $deficiencia->id }}" 
                                   id="deficiencia_{{ $deficiencia->id }}">
                            <label class="form-check-label" for="deficiencia_{{ $deficiencia->id }}">
                                {{ $deficiencia->nome }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Nenhuma deficiência cadastrada no sistema. Por favor, contacte o administrador.
                </div>
            @endif
            
            @error('deficiencias_selecionadas')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    @endif
</div>

<!-- Raça/Cor -->
<div class="form-group mb-3">
    <label for="raca_cor">Raça/Cor (autodeclaração)</label>
    <select class="form-control" id="raca_cor" wire:model="raca_cor">
        <option value="">Selecione a raça/cor</option>
        <option value="Branca">Branca</option>
        <option value="Preta">Preta</option>
        <option value="Parda">Parda</option>
        <option value="Amarela">Amarela</option>
        <option value="Indígena">Indígena</option>
        <option value="Não declarado">Não declarado</option>
    </select>
    @error('raca_cor')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>
                            <div class="form-group mb-3">
                                <label for="data_nascimento">Data de Nascimento</label>
                                <input type="date" class="form-control" id="data_nascimento" wire:model="data_nascimento" placeholder="dd/mm/aaaa" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="telefone">Telefone</label>
                                <input type="text" class="form-control" id="telefone" wire:model="telefone" placeholder="Ex: (12) 3456-7890">
                            </div>
                              <div class="form-group mb-3">
                                <label for="serie">Ano do Aluno(a)</label>
                                <div class="form-group mb-3">
                               <select class="form-control" id="ano_escolar" wire:model="ano_escolar">
                                    <option value="" disabled selected>Selecione o ano/série</option>
                                    
                                    <!-- Ensino Fundamental - Anos Iniciais -->
                                    <option value="1º Ano EF">1º Ano - Ensino Fundamental</option>
                                    <option value="2º Ano EF">2º Ano - Ensino Fundamental</option>
                                    <option value="3º Ano EF">3º Ano - Ensino Fundamental</option>
                                    <option value="4º Ano EF">4º Ano - Ensino Fundamental</option>
                                    <option value="5º Ano EF">5º Ano - Ensino Fundamental</option>
                                    
                                    <!-- Ensino Fundamental - Anos Finais -->
                                    <option value="6º Ano EF">6º Ano - Ensino Fundamental</option>
                                    <option value="7º Ano EF">7º Ano - Ensino Fundamental</option>
                                    <option value="8º Ano EF">8º Ano - Ensino Fundamental</option>
                                    <option value="9º Ano EF">9º Ano - Ensino Fundamental</option>
                                    
                                    <!-- Ensino Médio -->
                                    <option value="1º Ano EM">1º Ano - Ensino Médio</option>
                                    <option value="2º Ano EM">2º Ano - Ensino Médio</option>
                                    <option value="3º Ano EM">3º Ano - Ensino Médio</option>
                                    
                                    <!-- EJA - Educação de Jovens e Adultos -->
                                    <option value="EJA - Fase I">EJA - Fase I (1º a 5º ano)</option>
                                    <option value="EJA - Fase II">EJA - Fase II (6º a 9º ano)</option>
                                    <option value="EJA - Médio">EJA - Ensino Médio</option>
                                </select>
                            </div>
                             <div class="form-group mb-3">
                                <label for="aluno_cpf">CPF do Aluno(a)</label>
                                <input type="text" class="form-control" id="aluno_cpf" wire:model="aluno_cpf" placeholder="Ex: 123.456.789-00">
                            </div>
                               
                            </div>
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" wire:model="email" placeholder="Ex: exemplo@exemplo.com">
                            </div>
                            <div class="form-group mb-3">
                                <label for="contato_emergencia">Contato de Emergência</label>
                                <input type="text" class="form-control" id="contato_emergencia" wire:model="contato_emergencia" placeholder="Ex: (12) 3456-7890">
                            </div>

                            <h5 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-3">Informações de Endereço</h5>
                            <div class="form-group mb-3">
                                <label for="endereco">Endereço</label>
                                <input type="text" class="form-control" id="endereco" wire:model="endereco" placeholder="Ex: Rua das Flores, 123">
                            </div>
                            <div class="form-group mb-3">
                                <label for="pais_id">País</label>
                                <livewire:search-select type="pais" :selectedId="$pais_id" />
                            </div>
                            <div class="form-group mb-3">
                                <label for="cidade_id">Cidade</label>
                                <livewire:search-select type="cidade" :selectedId="$cidade_id" />
                            </div>
                            <div class="form-group mb-3">
                                <label for="bairro_id">Bairro</label>
                                <livewire:search-select type="bairro" :selectedId="$bairro_id" />
                            </div>
                            <div class="form-group mb-3">
                                <label for="distrito">Distrito</label>
                                <select class="form-control" id="distrito" wire:model="distrito">
                                    <option value="">Selecione um distrito</option>
                                    <option value="Sede">Sede</option>
                                    <option value="Abóbora">Abóbora</option>
                                    <option value="Carnaíba">Carnaíba do Sertão</option>
                                    <option value="Itamotinga">Itamotinga</option>
                                    <option value="Junqueiro">Junqueiro</option>
                                    <option value="Juremal">Juremal</option>
                                    <option value="Mandacaru">Mandacaru</option>
                                    <option value="Massaroca">Massaroca</option>
                                    <option value="Pinhões">Pinhões</option>
                                    <option value="Salitre">Salitre</option>
                                    <option value="Manoel Antônio">Manoel Antônio</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <a href="{{ route('Listar-Alunos') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('page_css')
<style>
    .card {
        transition: all 0.3s ease;
    }
    .card-border {
        border-left: 0.2rem solid #ff7744 !important;
        height: 55px;
    }
    .form-group {
        margin-bottom: 0.5rem;
    }
    .border-bottom {
        border-bottom: 2px solid #dee2e6 !important;
    }
    @media (max-width: 768px) {
        .form-group {
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush
@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire inicializado');
        
        // Monitora mudanças no radio button
        Livewire.on('$refresh', () => {
            console.log('Componente atualizado');
        });
        
        // Log para verificar se o radio está funcionando
        const radioSim = document.getElementById('deficiencia_sim');
        const radioNao = document.getElementById('deficiencia_nao');
        
        if (radioSim) {
            console.log('Radio SIM encontrado');
        }
        if (radioNao) {
            console.log('Radio NÃO encontrado');
        }
    });
</script>
@endpush