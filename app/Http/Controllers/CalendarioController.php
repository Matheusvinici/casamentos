<?php

namespace App\Http\Controllers;

use App\Models\Calendario;
use App\Models\Unidade;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CalendarioController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $calendarios = Calendario::where(function($query) use ($search) {
                $query->where('ano', 'LIKE', "%{$search}%")
                      ->orWhere('semestre', 'LIKE', "%{$search}%")
                      ->orWhereRaw("CONCAT(ano, '.', semestre) LIKE ?", ["%{$search}%"]);
            })
            ->orderBy('ano', 'DESC')
            ->orderBy('semestre', 'DESC')
            ->paginate(10);

        return view('calendarios.index', compact('calendarios'));
    }

    public function create()
    {
        $edit = false;
        $show = false;
        $calendario = new Calendario();
        $unidades = collect();
        return view('calendarios.create-edit-show', compact('edit', 'show', 'calendario', 'unidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ano' => 'required|numeric|min:2000|max:2100',
            'semestre' => ['required', Rule::in(['1', '2'])],
            'inicio' => 'required|date',
            'fim' => 'required|date|after:inicio',
            'total_dias_letivos' => 'required|integer|min:1',
            'ativo' => 'required|boolean',
        ], [
            'ano.required' => 'Campo ano é obrigatório.',
            'ano.numeric' => 'O ano deve ser um número.',
            'ano.min' => 'O ano deve ser a partir de 2000.',
            'ano.max' => 'O ano deve ser até 2100.',
            'semestre.required' => 'Campo semestre é obrigatório.',
            'semestre.in' => 'Semestre deve ser 1 ou 2.',
            'inicio.required' => 'Campo Início é obrigatório.',
            'fim.required' => 'Campo Fim é obrigatório.',
            'fim.after' => 'A data final deve ser posterior à data de início.',
            'ativo.required' => 'Campo ativo é obrigatório.',
            'total_dias_letivos.required' => 'Campo total de dias letivos é obrigatório.',
        ]);

        // Verifica se já existe calendário para o mesmo ano e semestre
        $exists = Calendario::where('ano', $request->ano)
            ->where('semestre', $request->semestre)
            ->exists();
            
        if ($exists) {
            return redirect()->back()
                ->withErrors(['ano' => 'Já existe um calendário cadastrado para este ano e semestre.'])
                ->withInput();
        }

        if ($request->ativo) {
            // Desativa todos os calendários
            Calendario::where('ativo', true)->update(['ativo' => false]);
            
            // Desativa todas as unidades ativas
            Unidade::where('ativo', true)->update(['ativo' => false]);
        }

        $calendario = Calendario::create($request->all());

        return redirect()->route('Editar-Calendario', $calendario->id)
            ->with('success', 'Calendário cadastrado com sucesso, agora você pode cadastrar as Unidades/Bimestres!');
    }

    public function show($id)
    {
        $show = true;
        $edit = false;
        $calendario = Calendario::findOrFail($id);
        $unidades = $calendario->unidades;
        return view('calendarios.create-edit-show', compact('calendario', 'show', 'edit', 'unidades'));
    }

    public function edit($id)
    {
        $edit = true;
        $show = false;
        $calendario = Calendario::findOrFail($id);
        $unidades = $calendario->unidades;
        return view('calendarios.create-edit-show', compact('calendario', 'unidades', 'edit', 'show'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ano' => 'required|numeric|min:2000|max:2100',
            'semestre' => ['required', Rule::in(['1', '2'])],
            'inicio' => 'required|date',
            'fim' => 'required|date|after:inicio',
            'total_dias_letivos' => 'required|integer|min:1',
            'ativo' => 'required|boolean',
        ], [
            'ano.required' => 'Campo ano é obrigatório.',
            'ano.numeric' => 'O ano deve ser um número.',
            'ano.min' => 'O ano deve ser a partir de 2000.',
            'ano.max' => 'O ano deve ser até 2100.',
            'semestre.required' => 'Campo semestre é obrigatório.',
            'semestre.in' => 'Semestre deve ser 1 ou 2.',
            'inicio.required' => 'Campo Início é obrigatório.',
            'fim.required' => 'Campo Fim é obrigatório.',
            'fim.after' => 'A data final deve ser posterior à data de início.',
            'ativo.required' => 'Campo ativo é obrigatório.',
            'total_dias_letivos.required' => 'Campo total de dias letivos é obrigatório.',
        ]);

        $calendario = Calendario::findOrFail($id);

        // Verifica se já existe outro calendário com mesmo ano e semestre
        $exists = Calendario::where('ano', $request->ano)
            ->where('semestre', $request->semestre)
            ->where('id', '!=', $id)
            ->exists();
            
        if ($exists) {
            return redirect()->back()
                ->withErrors(['ano' => 'Já existe outro calendário para este ano e semestre.'])
                ->withInput();
        }

        if ($request->ativo && !$calendario->ativo) {
            // Desativa todos os calendários
            Calendario::where('ativo', true)->update(['ativo' => false]);
            
            // Desativa todas as unidades ativas
            Unidade::where('ativo', true)->update(['ativo' => false]);
        }

        $calendario->update($request->all());

        return redirect()->route('Listar-Calendarios')
            ->with('success', 'Calendário atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $calendario = Calendario::findOrFail($id);
        
        // Verifica se há unidades vinculadas
        if ($calendario->unidades()->count() > 0) {
            return redirect()->route('Listar-Calendarios')
                ->with('error', 'Não é possível excluir o calendário porque existem unidades vinculadas a ele. Exclua as unidades primeiro.');
        }
        
        $calendario->delete();
        
        return redirect()->route('Listar-Calendarios')
            ->with('success', 'Calendário excluído com sucesso!');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $calendarios = Calendario::where(function($query) use ($search) {
                $query->where('ano', 'LIKE', "%{$search}%")
                      ->orWhere('semestre', 'LIKE', "%{$search}%")
                      ->orWhereRaw("CONCAT(ano, '.', semestre) LIKE ?", ["%{$search}%"]);
            })
            ->orderBy('ano', 'DESC')
            ->orderBy('semestre', 'DESC')
            ->get();

        $html = view('calendarios.partials.table', compact('calendarios'))->render();
        return response()->json(['data' => $html]);
    }

    public function toggleActive($id)
    {
        $calendario = Calendario::findOrFail($id);

        if (!$calendario->ativo) {
            // Desativa todos os calendários e unidades
            Calendario::where('ativo', true)->update(['ativo' => false]);
            Unidade::where('ativo', true)->update(['ativo' => false]);
            
            // Ativa este calendário
            $calendario->update(['ativo' => true]);
            
            $message = 'Calendário ativado com sucesso! Todas as unidades foram desativadas.';
        } else {
            $calendario->update(['ativo' => false]);
            $message = 'Calendário desativado com sucesso!';
        }

        return redirect()->route('Listar-Calendarios')->with('success', $message);
    }
    
    /**
     * Ativa uma unidade específica do calendário
     */
    public function ativarUnidade($calendarioId, $unidadeId)
    {
        $calendario = Calendario::findOrFail($calendarioId);
        $unidade = Unidade::findOrFail($unidadeId);
        
        // Verifica se a unidade pertence ao calendário
        if ($unidade->calendario_id != $calendario->id) {
            return redirect()->route('Editar-Calendario', $calendarioId)
                ->with('error', 'Esta unidade não pertence a este calendário.');
        }
        
        // Desativa todas as unidades ativas
        Unidade::where('ativo', true)->update(['ativo' => false]);
        
        // Ativa esta unidade
        $unidade->update(['ativo' => true]);
        
        return redirect()->route('Editar-Calendario', $calendarioId)
            ->with('success', 'Unidade ativada com sucesso!');
    }
}