@foreach ($calendarios as $calendario)
    <tr class="text-left pointer" data-calendar-id="{{ $calendario->id }}">
        <td data-label="Ano">{{ $calendario->ano }}</td>
        <td data-label="Ativo">{{ $calendario->ativo ? 'Ativo' : 'Inativo' }}</td>
        <td data-label="Ações">
            @can('Ver-Calendario')
                <a href="{{ route('Ver-Calendario', $calendario->id) }}" class="btn btn-sm btn-info">Visualizar</a>
            @endcan
            @can('Editar-Calendario')
                <a href="{{ route('Editar-Calendario', $calendario->id) }}" class="btn btn-sm btn-primary">Editar</a>
                <form action="{{ route('Toggle-Calendario-Active', $calendario->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-sm {{ $calendario->ativo ? 'btn-warning' : 'btn-success' }}">
                        {{ $calendario->ativo ? 'Desativar' : 'Ativar' }}
                    </button>
                </form>
            @endcan
        </td>
    </tr>
@endforeach