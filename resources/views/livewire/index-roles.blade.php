<div>
    <div class="mb-3">
        <a href="{{ route('Criar-Papel') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Adicionar
        </a>
        <input wire:model.live.debounce.500ms="search" type="text" class="form-control float-right" style="width: 200px;" placeholder="Pesquisar...">
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Nome</th>
                    <th class="text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td class="text-right">
                            @can('Ver-Papel')
                                <a href="{{ route('Ver-Papel', $item->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endcan
                            @can('Editar-Papel')
                                <a href="{{ route('Editar-Papel', $item->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endcan
                            @can('Deletar-Papel')
                                <form action="{{ route('Deletar-Papel', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar {{ $item->name }}?');">
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
                        <td colspan="2" class="text-center">
                            {{ __('Nenhum papel localizado') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $data->onEachSide(1)->links() }}
    </div>
</div>