<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Distrito;
use App\Models\Estado;
use App\Models\Cidade;

class DistritoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
                // distritos = distrito::all();
        $distritos = Distrito::with('cidades')->where('cidade_id', 'LIKE', "%{$search}%")
        ->orWhere('nome', 'LIKE', "%{$search}%")
        ->orWhere('codigo_ibge', 'LIKE', "%{$search}%")
        ->paginate(10);

                return view('distritos.index', compact('distritos'));
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
        $estados = Estado::orderBy('nome')->get();
        $cidades = Cidade::with('estados')->orderBy('nome')->get();
        return view('distritos.create-edit-show', compact('edit', 'show', 'estados', 'cidades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //     // vairaveis para validação unica
        //    $field1 = $request->input('field1');
        //    $field2 = $request->input('field2');
        //    $field3 = $request->input('field3');

        $request->validate(

            [
                // 'fieldname' => 'required|unique:distritos',
                // //  exemplo de validação unica início
                // 'field1' => [
                // 'required',
                //     Rule::unique('distritos')->where(function ($query) use($field1, $field2, $field3) {
                //         return $query
                //         ->where('field1', $field1)
                //         ->where('field2', $field2)
                //         ->where('field3', $field3)
                //         ;
                //             }),
                //  ], // validação unica fim

            'cidade_id' => 'required',
            'nome' => 'required',
            'codigo_ibge' => 'required',
                ],
                    [
            'cidade_id.required' => 'Campo cidade_id é obrigatório.',
            'nome.required' => 'Campo nome é obrigatório.',
            'codigo_ibge.required' => 'Campo codigo_ibge é obrigatório.',
            //  'field1.unique' => 'Já existe um registro distrito com esses dados',

            ]
        );

        $input = $request->all();
        $distritos = Distrito::create($input);

        //    // exemplo de resgitro array de dados no banco de dados
        //    foreach($request->input('field1') as $arr){

        //    $Distrito = new Distrito;
        //    $Distrito->field1 = $arr;
        //    $Distrito->field2 = $request->input('field2');
        //    $Distrito->field3 = $request->input('field3');
        //    $Distrito->save();
        //      }

        return redirect('/distritos')->with('success', 'Registro cadastrado com sucesso');
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
        $estados = Estado::orderBy('nome')->get();
        $distrito = Distrito::findOrFail($id);
        $cidades = Cidade::all();

        return view('distritos.create-edit-show', compact('estados', 'distrito', 'show', 'edit', 'cidades'));
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
        $estados = Estado::orderBy('nome')->get();
        $distrito = Distrito::findOrFail($id);
        $cidades = Cidade::all();

        return view('distritos.create-edit-show', compact('estados', 'distrito', 'edit', 'show', 'cidades'));
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
        'cidade_id' => 'required',
        'nome' => 'required',
        'codigo_ibge' => 'required',

            ],
            [
        'cidade_id.required' => 'Campo cidade_id é obrigatório.',
        'nome.required' => 'Campo nome é obrigatório.',
        'codigo_ibge.required' => 'Campo codigo_ibge é obrigatório.',

        ]);
        Distrito::whereId($id)->update($validatedData);

        // Prepara retorno para a index
        $distritos = Distrito::all();
        return redirect('/distritos')->with('success', 'Registro atualizado com sucesso');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete($id)
    {
        $distrito = Distrito::find($id);

        return view('distritos.delete', compact('distrito'));
    
    }

    public function destroy($id)
    {
        $distrito = Distrito::findOrFail($id);
        $distrito->delete();
        // $distritos = Distrito::all();

        return redirect('/distritos')->with('success', 'Registro excluído com sucesso');

        // return view('distritos.browse', compact('distritos'))->with('success', 'Registro excluído com sucesso');
    }
}

