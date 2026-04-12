<?php

namespace App\Http\Controllers;

use App\Models\Unidade;
use Illuminate\Http\Request;

class UnidadeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'calendario_id' => 'required|exists:calendarios,id',
            'nome' => 'required|string|max:255|unique:unidades,nome,NULL,id,calendario_id,' . $request->calendario_id,
            'sigla' => 'required|string|max:10',
            'data_inicio' => 'required|date',
            'data_final' => 'required|date|after:data_inicio',
            'data_limite_lancamento' => 'nullable|date|after_or_equal:data_final',
            'qtd_dias_letivos' => 'required|integer|min:1',
            'ativo' => 'required|boolean',
        ]);

        Unidade::create($request->all());

        return redirect()->back()->with('success', 'Unidade adicionada com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $unidade = Unidade::findOrFail($id);

        $request->validate([
            'nome' => ['required', 'string', 'max:255', Rule::unique('unidades')->ignore($id)->where('calendario_id', $unidade->calendario_id)],
            'sigla' => 'required|string|max:10',
            'data_inicio' => 'required|date',
            'data_final' => 'required|date|after:data_inicio',
            'data_limite_lancamento' => 'nullable|date|after_or_equal:data_final',
            'qtd_dias_letivos' => 'required|integer|min:1',
            'ativo' => 'required|boolean',
        ]);

        $unidade->update($request->all());

        return redirect()->back()->with('success', 'Unidade atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $unidade = Unidade::findOrFail($id);
        $unidade->delete();

        return redirect()->back()->with('success', 'Unidade excluída com sucesso!');
    }
}