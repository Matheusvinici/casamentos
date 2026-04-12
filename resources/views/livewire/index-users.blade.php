<div>
    <div class="mb-3">
        <a href="{{ route('Criar Usuario') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Adicionar
        </a>
        <input wire:model.live.debounce.500ms="search" type="text" class="form-control float-right" style="width: 200px;" placeholder="Pesquisar...">
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Nome</th>
                    <th class="d-none d-sm-table-cell">Email</th>
                    <th class="text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td onclick="openRecord('{{ route('Mostrar Usuario', $item->id) }}')" style="cursor: pointer;">
                            {{ $item->name }} <br class="d-sm-none">
                            <span class="d-sm-none text-muted">{{ $item->email ?? '' }}</span>
                        </td>
                        <td onclick="openRecord('{{ route('Mostrar Usuario', $item->id) }}')" class="d-none d-sm-table-cell" style="cursor: pointer;">
                            {{ $item->email }}
                        </td>
                        <td class="text-right">
                            @can('Editar Usuario')
                                <a href="{{ route('Editar Usuario', $item) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endcan
                            @can('Deletar Usuario')
                                <form action="{{ route('Deletar Usuario', $item) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar {{ $item->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">
                            {{ __('Nenhum usuário localizado') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $data->onEachSide(1)->links() }}
    </div>

    <script>
        function openRecord(url) {
            window.location.href = url;
        }
    </script>
</div>