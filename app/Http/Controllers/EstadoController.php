<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Estado;
use App\Models\Pais;

class EstadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        // estados = estado::all();
        $estados = Estado::where('pais_id', 'LIKE', "%{$search}%")
        ->orWhere('nome', 'LIKE', "%{$search}%")
        ->orWhere('sigla', 'LIKE', "%{$search}%")
        ->orWhere('codigo_ibge', 'LIKE', "%{$search}%")
        ->paginate(10);

        return view('estados.index', compact('estados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $edit = false;
        $show = false;
        $paises = Pais::all();
        return view('estados.create-edit-show', compact('edit', 'show', 'paises'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate(

            [

        'pais_id' => 'required',
        'nome' => 'required',
        'sigla' => 'required',
        'codigo_ibge' => 'required',
            ],
                [
        'pais_id.required' => 'Campo pais_id é obrigatório.',
        'nome.required' => 'Campo nome é obrigatório.',
        'sigla.required' => 'Campo sigla é obrigatório.',
        'codigo_ibge.required' => 'Campo codigo_ibge é obrigatório.',
        //  'field1.unique' => 'Já existe um registro estado com esses dados',

            ]
        );

        $input = $request->all();
        $estados = Estado::create($input);

       

        return redirect('/estados')->with('success', 'Registro cadastrado com sucesso');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show = true;
        $edit = false;
        $estado = Estado::findOrFail($id);
        $paises = Pais::all();

        return view('estados.create-edit-show', compact('estado', 'show', 'edit', 'paises'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function edit($id)
    {
        $edit = true;
        $show = false;
        $estado = Estado::findOrFail($id);
        $paises = Pais::all();

        return view('estados.create-edit-show', compact('estado', 'edit', 'show', 'paises'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
        'pais_id' => 'required',
        'nome' => 'required',
        'sigla' => 'required',
        'codigo_ibge' => 'required',

            ],
            [
        'pais_id.required' => 'Campo pais_id é obrigatório.',
        'nome.required' => 'Campo nome é obrigatório.',
        'sigla.required' => 'Campo sigla é obrigatório.',
        'codigo_ibge.required' => 'Campo codigo_ibge é obrigatório.',

        ]);
        Estado::whereId($id)->update($validatedData);

        // Prepara retorno para a index
        $estados = Estado::all();
        return redirect('/estados')->with('success', 'Registro atualizado com sucesso');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete($id)
    {
        $estado = Estado::find($id);

        return view('estados.delete', compact('estado'));
    
    }

    public function destroy($id)
    {
        $estado = Estado::findOrFail($id);
        $estado->delete();
        // $estados = Estado::all();

        return redirect('/estados')->with('success', 'Registro excluído com sucesso');

        // return view('estados.browse', compact('estados'))->with('success', 'Registro excluído com sucesso');
    }
}

