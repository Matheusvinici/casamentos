<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Aluno;
use App\Models\Turma;
use App\Models\AlunoFoto;
use Illuminate\Support\Facades\Storage;

class AlunoFotoCaptura extends Component
{
    public $turmas = [];
    public $turma_id = null;
    public $alunos = [];

    public function mount()
    {
        $this->turmas = Turma::all();
    }

    public function updatedTurmaId($value)
    {
        if ($value) {
            $turma = Turma::find($value);
            $this->alunos = $turma->alunos()->with('fotos')->get();
        } else {
            $this->alunos = [];
        }
    }

    public function salvarFoto($alunoId, $fotoBase64, $descriptorJson)
    {
        $aluno = Aluno::findOrFail($alunoId);

        // Remover o cabeçalho "data:image/jpeg;base64,"
        if (strpos($fotoBase64, ';base64,') !== false) {
            $image_parts = explode(";base64,", $fotoBase64);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = isset($image_type_aux[1]) ? $image_type_aux[1] : 'jpeg';
            $image_base64 = base64_decode($image_parts[1]);

            $fileName = 'fotos/aluno_' . $alunoId . '_' . time() . '.' . $image_type;
            
            Storage::disk('public')->put($fileName, $image_base64);

            // Inativar fotos anteriores do aluno
            AlunoFoto::where('aluno_id', $alunoId)->update(['ativo' => false]);

            // Salvar no BD
            AlunoFoto::create([
                'aluno_id' => $alunoId,
                'foto_path' => $fileName,
                'face_descriptor' => $descriptorJson,
                'ativo' => true,
            ]);

            session()->flash('success', 'Foto cadastrada com sucesso para o aluno ' . $aluno->nome);
            
            // Recarregar alunos
            $this->updatedTurmaId($this->turma_id);
        }
    }

    public function removerFoto($fotoId)
    {
        $foto = AlunoFoto::findOrFail($fotoId);
        
        // Remover do Storage
        if (Storage::disk('public')->exists($foto->foto_path)) {
            Storage::disk('public')->delete($foto->foto_path);
        }
        
        $foto->delete();
        
        session()->flash('success', 'Foto removida com sucesso!');
        $this->updatedTurmaId($this->turma_id);
    }

    public function render()
    {
        return view('livewire.aluno-foto-captura')->layout('layouts.app');
    }
}
