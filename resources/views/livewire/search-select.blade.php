<div class="relative">
    <input 
        wire:model.live.debounce.300ms="search" 
        type="text" 
        class="form-control" 
        placeholder="{{ $placeholder }}" 
        autocomplete="off"
    />
    
    @if(!empty($results))
        <ul class="absolute z-10 bg-white border border-gray-300 w-full mt-1 max-h-48 overflow-y-auto rounded shadow-lg">
            @foreach($results as $result)
                <li 
                    wire:click="selectItem({{ $result['id'] }})" 
                    class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
                >
                    {{ $result['nome'] }}
                </li>
            @endforeach
        </ul>
    @endif

    <input type="hidden" name="{{ $type }}_id" value="{{ $selectedId }}">
</div>