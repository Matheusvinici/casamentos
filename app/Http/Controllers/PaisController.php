<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Pais;

class PaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
            // paises = pais::all();
    $paises = Pais::where('nome', 'LIKE', "%{$search}%")
    ->orWhere('codigo_ibge', 'LIKE', "%{$search}%")
    ->paginate(10);

            return view('paises.index', compact('paises'));
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
        return view('paises.create-edit-show', compact('edit', 'show'));
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
                // 'fieldname' => 'required|unique:paises',
                // //  exemplo de validação unica início
                // 'field1' => [
                // 'required',
                //     Rule::unique('paises')->where(function ($query) use($field1, $field2, $field3) {
                //         return $query
                //         ->where('field1', $field1)
                //         ->where('field2', $field2)
                //         ->where('field3', $field3)
                //         ;
                //             }),
                //  ], // validação unica fim

            'nome' => 'required',
            'codigo_ibge' => 'required',
                ],
                    [
            'nome.required' => 'Campo nome é obrigatório.',
            'codigo_ibge.required' => 'Campo codigo_ibge é obrigatório.',
            //  'field1.unique' => 'Já existe um registro pais com esses dados',

            ]
        );

        $input = $request->all();
        $paises = Pais::create($input);

        //    // exemplo de resgitro array de dados no banco de dados
        //    foreach($request->input('field1') as $arr){

        //    $Pais = new Pais;
        //    $Pais->field1 = $arr;
        //    $Pais->field2 = $request->input('field2');
        //    $Pais->field3 = $request->input('field3');
        //    $Pais->save();
        //      }

        return redirect('/paises')->with('success', 'Registro cadastrado com sucesso');
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
        $pais = Pais::findOrFail($id);

        return view('paises.create-edit-show', compact('pais', 'show', 'edit'));
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
        $pais = Pais::findOrFail($id);

        return view('paises.create-edit-show', compact('pais', 'edit', 'show'));
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
    'nome' => 'required',
    'codigo_ibge' => 'required',

        ],
        [
    'nome.required' => 'Campo nome é obrigatório.',
    'codigo_ibge.required' => 'Campo codigo_ibge é obrigatório.',

    ]);
        Pais::whereId($id)->update($validatedData);

        // Prepara retorno para a index
        $paises = Pais::all();
        return redirect('/paises')->with('success', 'Registro atualizado com sucesso');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete($id)
    {
        $pais = Pais::find($id);

        return view('paises.delete', compact('pais'));
    
    }

    public function destroy($id)
    {
        $pais = Pais::findOrFail($id);
        $pais->delete();
        // $paises = Pais::all();

        return redirect('/paises')->with('success', 'Registro excluído com sucesso');

        // return view('paises.browse', compact('paises'))->with('success', 'Registro excluído com sucesso');
    }
}

