<?php

system('composer dump-autoload');

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$fileWeb = 'routes/web.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

if (!file_exists("artisan")) {
    echo "Execute este script a partir do diretório raiz do seu projeto Laravel.";
    exit(1);
}

$ModelName = readline("Digite o nome da Model: ");

if (class_exists("App\Models\\{$ModelName}")) {
    echo "O modelo já existe.\n";
} else {
    $ModelNamePlural = readline("Digite o nome da Model no Plural: ");

    $tableName = Str::snake($ModelNamePlural); // Ex.: AntdNaturezas -> antd_naturezas
    $modelnamesingularminusculo = strtolower($ModelName);
    $modelnamepluralminusculo = strtolower($ModelNamePlural);

    // Adiciona espaço no nome do modelo para exibição
    $ModelNameWithSpace = preg_replace('/([a-z])([A-Z])/', '$1 $2', $ModelName); // Ex.: AntdNatureza -> Antd Natureza
    $ModelNamePluralWithSpace = preg_replace('/([a-z])([A-Z])/', '$1 $2', $ModelNamePlural); // Ex.: AntdNaturezas -> Antd Naturezas

    $diretorios = [
        "app/Models",
        "app/Http/Controllers",
        "app/Http/Controllers/Search",
        "resources/views/$modelnamepluralminusculo",
        "crudRemove",
    ];

    foreach ($diretorios as $diretorio) {
        if (!is_dir($diretorio)) {
            if (mkdir($diretorio, 0777, true)) {
                echo "Diretório criado com sucesso: $diretorio \n";
            } else {
                echo "Erro ao criar o diretório: $diretorio \n";
                exit(1);
            }
        } else {
            echo "O diretório já existe: $diretorio \n";
        }
    }

    system("php artisan make:model $ModelName");
    system("php artisan make:migration create_{$tableName}_table");
    echo "Migração gerada com sucesso para a tabela $tableName.\n";

    $migrationFilePath = database_path("migrations") . "/" . now()->format('Y_m_d_*') . "_create_{$tableName}_table.php";
    system("code " . $migrationFilePath);

    echo "Edite a migração manualmente. Quando terminar, pressione Enter para continuar...";
    fgets(STDIN);

    system("php artisan migrate");

    $migration = DB::table('migrations')->where('migration', 'like', "%create_{$tableName}_table%")->first();
    $migrationFilePathRollback = "database/migrations/" . $migration->migration . ".php";

    $crudRemove = "<?php
    
    \$filesPath = [
        'app/Models/$ModelName.php', 
        'app/Http/Controllers/{$ModelName}Controller.php',
        'app/Http/Controllers/Search/Search{$ModelName}Controller.php',
        'resources/views/{$modelnamepluralminusculo}/create-edit-show.blade.php',
        'resources/views/{$modelnamepluralminusculo}/index.blade.php',
    ];
    foreach (\$filesPath as \$file) {
        if (file_exists(\$file)) {
            unlink(\$file);
            echo \"Arquivo \$file foi removido.\\n\";
        } else {
            echo \"Arquivo \$file não existe.\\n\";
        }
    }
    if (rmdir('resources/views/{$modelnamepluralminusculo}')) {
        echo 'Pasta removida com sucesso.';
    } else {
        echo 'Falha ao remover a pasta. Certifique-se de que a pasta está vazia.';
    }
    if (file_exists('$migrationFilePathRollback')) {
        system(\"php artisan migrate:rollback --path=$migrationFilePathRollback\");
        unlink('$migrationFilePathRollback');
        echo \"Arquivo $migrationFilePathRollback foi removido.\\n\";
    } else {
        echo \"Arquivo $migrationFilePathRollback não existe.\\n\";
    }
    \$fileWeb = 'routes/web.php';
    \$conteudo = file_get_contents('\$fileWeb');
    \$trechoInicio = '// Routes for $ModelName START';
    \$trechoFim = '// Routes for $ModelName END';
    \$inicio = strpos(\$conteudo, \$trechoInicio);
    \$fim = strpos(\$conteudo, \$trechoFim) + strlen(\$trechoFim);
    if (\$inicio !== false && \$fim !== false) {
        \$novoConteudo = substr_replace(\$conteudo, '', \$inicio, \$fim - \$inicio);
        file_put_contents(\$fileWeb, \$novoConteudo);
        echo 'Rotas removidas com sucesso.\\n';
    } else {
        echo 'Rotas não encontradas.\\n';
    }
    unlink('crudRemove/{$ModelName}.php');
    ?>";
    file_put_contents("crudRemove/{$ModelName}.php", $crudRemove);

    $Fields = array_diff(DB::getSchemaBuilder()->getColumnListing($tableName), ['id', 'created_at', 'updated_at']);
    $FirstColumn = reset($Fields);
    $remainingFields = array_slice($Fields, 1);

    // Model
    $ModelCode = "<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    class $ModelName extends Model
    {
        use HasFactory;
        protected \$table = '$tableName';
        protected \$fillable = [
    ";
    foreach ($Fields as $field) {
        $ModelCode .= "        '$field',\n";
    }
    $ModelCode .= "    ];
    }";

    // Controller
    $ControllerCode = "<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\\$ModelName;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Validation\Rule;
    class {$ModelName}Controller extends Controller
    {
        public function index()
        {
            \$$modelnamepluralminusculo = $ModelName::paginate(10);
            return view('$modelnamepluralminusculo.index', compact('$modelnamepluralminusculo'));
        }
        public function create()
        {
            \$create = true;
            \$edit = false;
            \$show = false;
            return view('$modelnamepluralminusculo.create-edit-show', compact('create', 'edit', 'show'));
        }
        public function store(Request \$request)
        {
            \$request->validate([
                '$FirstColumn' => 'required|unique:$tableName',
    ";
    foreach ($remainingFields as $field) {
        $ControllerCode .= "                '$field' => 'required',\n";
    }
    $ControllerCode .= "            ], [
                '$FirstColumn.unique' => 'Este $FirstColumn já está em uso',
    ";
    foreach ($Fields as $field) {
        $ControllerCode .= "                '$field.required' => 'O campo $field é obrigatório',\n";
    }
    $ControllerCode .= "            ]);
            $ModelName::create(\$request->all());
            return back()->with('success', 'Registro criado com sucesso!');
        }
        public function show(\$id)
        {
            \$create = false;
            \$edit = false;
            \$show = true;
            \$$modelnamesingularminusculo = $ModelName::findOrFail(\$id);
            return view('$modelnamepluralminusculo.create-edit-show', compact('create', 'edit', 'show', '$modelnamesingularminusculo'));
        }
        public function edit(\$id)
        {
            \$create = false;
            \$edit = true;
            \$show = false;
            \$$modelnamesingularminusculo = $ModelName::findOrFail(\$id);
            return view('$modelnamepluralminusculo.create-edit-show', compact('create', 'edit', 'show', '$modelnamesingularminusculo'));
        }
        public function update(Request \$request, \$id)
        {
            \$request->validate([
                '$FirstColumn' => 'required|unique:$tableName,$FirstColumn,'.\$id,
    ";
    foreach ($remainingFields as $field) {
        $ControllerCode .= "                '$field' => 'required',\n";
    }
    $ControllerCode .= "            ], [
                '$FirstColumn.unique' => 'Este $FirstColumn já está em uso',
    ";
    foreach ($Fields as $field) {
        $ControllerCode .= "                '$field.required' => 'O campo $field é obrigatório',\n";
    }
    $ControllerCode .= "            ]);
            \$record = $ModelName::findOrFail(\$id);
            \$record->update(\$request->all());
            return redirect('/$modelnamepluralminusculo')->with('success', 'Registro atualizado com sucesso!');
        }
        public function destroy(\$id)
        {
            \$record = $ModelName::findOrFail(\$id);
            \$record->delete();
            return response()->json(['success' => true, 'message' => 'Registro excluído com sucesso']);
        }
    }";

    // Search Controller
    $SearchCode = "<?php
    namespace App\Http\Controllers\Search;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Models\\$ModelName;
    class Search{$ModelName}Controller extends Controller
    {
        public function search(Request \$request)
        {
            if (strlen(\$request->search) > 3) {
                if (\$request->ajax()) {
                    \$output = '';
                    \$$modelnamepluralminusculo = $ModelName::where('$FirstColumn', 'LIKE', '%'.\$request->search.'%')
                        ->limit(15)
                        ->get();
                    if (\$$modelnamepluralminusculo) {
                        foreach (\$$modelnamepluralminusculo as \$key => \$$modelnamesingularminusculo) {
                            \$output .= '<tr onclick=\"abreregistro(\''.route('Ver-$ModelName', \$".$modelnamesingularminusculo."->id).'\');\" class=\"text-left pointer\">';
    ";
    foreach ($Fields as $field) {
        $SearchCode .= "                            \$output .= '<td>'.\$${modelnamesingularminusculo}->$field.'</td>';\n";
    }
    $SearchCode .= "                            \$output .= '</tr>';
                        }
                        return response()->json(['data' => \$output]);
                    }
                }
            }
        }
    }";

    // Index View (com campo de busca e espaço no nome)
    $IndexCode = "@extends('layouts.app')
@section('css')
<meta name='csrf-token' content='{{ csrf_token() }}'>
@stop
@section('content')
<div class='card'>
    <div class='card-header '> 
        <div class='page-title'>
            <div class='page-title-heading'>
                <h6>Lista de $ModelNamePluralWithSpace</h6>
            </div>
            <div class='page-title-actions'>
                <div class='float-left mr-2'>
                    <div>
                        <input type='text' class='form-control search' name='search' id='search' placeholder='Digite algo'/>
                    </div>
                </div>
                <a href='{{ route('Criar-$ModelName') }}' type='button' class='btn btn-primary btn-sm float-right'>Adicionar</a>
            </div>
        </div>
    </div>
    <div class='card-body'>
        <table class='table table-hover'>
            <thead>
                <tr>";
    foreach ($Fields as $field) {
        $upperField = ucfirst($field);
        $IndexCode .= "
                    <th>$upperField</th>";
    }
    $IndexCode .= "
                </tr>
            </thead>
            <tbody>
                @foreach(\$$modelnamepluralminusculo as \$$modelnamesingularminusculo)
                <tr onclick=\"abreregistro('{{ route('Ver-$ModelName', \$".$modelnamesingularminusculo."->id) }}');\" class='text-left pointer'>";
    foreach ($Fields as $field) {
        $IndexCode .= "
                    <td>{{ \$".$modelnamesingularminusculo."->$field }}</td>";
    }
    $IndexCode .= "
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
@section('javascript')
<script>
    function abreregistro(url) {
        window.location.href = url;
    }
    \$('#search').on('keyup', function() {
        \$value = \$(this).val();
        \$.ajax({
            type: 'get',
            url: '{{route(\"Search-$ModelName\")}}',
            data: {'search': \$value},
            success: function(data) {
                \$('tbody').html(data.data);
            }
        });
    });
</script>
@stop";

    // Create-Edit-Show View (com espaço no nome)
    $CESCode = "@extends('layouts.app')
@section('content')

<div class='mt-2'>
    @include('layouts.partials.messages')
</div>

<div class='card'>
    <div class='card-header'>
        <h6>
            @if (\$show)
                $ModelNameWithSpace
            @else
                {{ \$edit ? 'Editar ' : 'Adicionar ' }} $ModelNameWithSpace
            @endif
        </h6>
        <div class='float-right page-title-actions'>

        <a href='{{ route('Listar-$ModelNamePlural') }}' class='btn btn-sm btn-outline-info'><i class='bi bi-list'></i></a>
                    
            @if (!\$edit && !\$create)
                <a href='{{ route('Editar-$ModelName', \$".$modelnamesingularminusculo."->id ?? '') }}'
                    class='btn btn-sm btn-outline-primary'><i class='bi bi-pencil-square'></i></a>
            @endif
            @can('Deletar-$ModelName')
                @if (\$edit || \$show)
                    <a data-toggle='modal' id='smallButton' data-target='#smallModal'
                        data-attr='{{ route('Deletar-$ModelName', \$".$modelnamesingularminusculo."->id ?? '') }}'
                        onclick=\"excluiregistro('{{ \$".$modelnamesingularminusculo."->id ?? '' }}')\"
                        title='Deletar $ModelName'>
                        <i class='btn btn-sm btn-outline-danger'><i class='bi bi-trash-fill'></i></i>
                    </a>
                @endif
            @endcan

        </div>

    </div>
    <div class='card-body'>
        <form action='{{ \$edit ? route('Atualizar-$ModelName', \$".$modelnamesingularminusculo."->id) : route('Gravar-$ModelName') }}' method='post'>
            @csrf
            @if (\$edit)
                @method('PUT')
            @endif
            <div class='row g-3'>";
    foreach ($Fields as $field) {
        $upperField = ucfirst($field);
        $CESCode .= "
                <div class='col-md-6'>
                    <label for='$field'>$upperField</label>
                    <input {{ \$show ? 'disabled' : '' }} value='{{ old('$field', \$edit || \$show ? \$".$modelnamesingularminusculo."->$field : '') }}' type='text' class='form-control' id='$field' name='$field' placeholder='$field' required>
                </div>";
    }
    $CESCode .= "
                @if (!\$show)
                    <div>
                        <button type='submit' class='btn btn-primary btn-sm float-right'>{{ \$edit ? 'Atualizar' : 'Adicionar' }}</button>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
@stop

@section('javascripts')

<script>
        function excluiregistro(id) {
            swal({
                    title: `Deseja realmente 'Excluir'?`,
                    text: 'Esta ação EXCLUIRÁ o registro selecionado.',
                    icon: 'warning',
                    buttons: ['Cancelar', true],
                    dangerMode: true,
                })
                .then((willChange) => {
                    if (willChange) {

                        var url = '{{ route('Deletar-$ModelName', ':id') }}';
                        url = url.replace(':id', id);
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            dataType: 'json',
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'id': id
                            },
                            success: function(response) {
                                window.location.href = '{{ route('Listar-$ModelNamePlural') }}';
                            },
                            error: function(xhr, status, error) {
                                var errorMessage = xhr.responseJSON.error ||
                                    'Ocorreu um erro ao excluir o registro';
                            }
                        });
                    }
                });

        };
</script>
@stop
";

    // Gravação dos arquivos
    $files = [
        "app/Models/$ModelName.php" => $ModelCode,
        "app/Http/Controllers/{$ModelName}Controller.php" => $ControllerCode,
        "app/Http/Controllers/Search/Search{$ModelName}Controller.php" => $SearchCode,
        "resources/views/$modelnamepluralminusculo/index.blade.php" => $IndexCode,
        "resources/views/$modelnamepluralminusculo/create-edit-show.blade.php" => $CESCode,
    ];

    foreach ($files as $path => $content) {
        if (file_put_contents($path, $content) === false) {
            echo "Erro ao criar o arquivo $path. Verifique as permissões do diretório.\n";
            exit(1);
        } else {
            echo "Arquivo $path criado com sucesso.\n";
        }
    }

    // Atualização das rotas
    $contentWeb = file_get_contents($fileWeb);
    $contentWeb = str_replace('});//ScriptnewroutecrudCreateScript', "
    // Routes for $ModelName START
    Route::group(['prefix' => '$modelnamepluralminusculo'], function () {
        Route::get('/', '{$ModelName}Controller@index')->name('Listar-$ModelNamePlural');
        Route::get('/create', '{$ModelName}Controller@create')->name('Criar-$ModelName');
        Route::post('/store', '{$ModelName}Controller@store')->name('Gravar-$ModelName');
        Route::get('/show/{id}', '{$ModelName}Controller@show')->name('Ver-$ModelName');
        Route::put('/update/{id}', '{$ModelName}Controller@update')->name('Atualizar-$ModelName');
        Route::get('/edit/{id}', '{$ModelName}Controller@edit')->name('Editar-$ModelName');
        Route::get('/search', 'Search\Search{$ModelName}Controller@search')->name('Search-$ModelName');
        Route::delete('/destroy/{id}', '{$ModelName}Controller@destroy')->name('Deletar-$ModelName');
    });
    // Routes for $ModelName END
    });//ScriptnewroutecrudCreateScript", $contentWeb);
    file_put_contents($fileWeb, $contentWeb);

    system("
    bash clearCaches.sh
    php artisan permission:create-permission-routes
    php artisan db:seed --class=AdminAssignAllPermissions
    ");

    echo "\e[32mMODELO GERADO COM SUCESSO! Confirme se as rotas foram criadas no arquivo routes/web.php. \e[0m\n";
    echo "\e[32mAcesso em http://127.0.0.1:8000/$modelnamepluralminusculo \e[0m\n";
}