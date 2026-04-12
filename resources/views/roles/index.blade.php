@extends('layouts.app')

@section('title', 'Papéis')

@section('content-title', __('Papéis'))

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('index-roles')
        </div>
    </div>
@endsection