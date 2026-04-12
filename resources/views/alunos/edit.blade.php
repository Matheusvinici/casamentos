@extends('layouts.app')

@section('content')
<livewire:aluno-form :alunoId="$aluno->id" />
@endsection