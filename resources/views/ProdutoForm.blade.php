@extends('base.app')

@section('conteudo')
    @php
        if (!empty($produto->id)) {
            $route = route('produto.update', $produto->id);
        } else {
            $route = route('produto.store');
        }
    @endphp
@section('tituloPagina', 'Formulário produto')
<h1>Formulário produto</h1>

<div class="col">
    <div class="row">
        <form action='{{ $route }}' method="POST" enctype="multipart/form-data">
            @csrf
            @if (!empty($produto->id))
                @method('PUT')
            @endif

            <input type="hidden" name="id"
                value="@if (!empty(old('id'))) {{ old('id') }} @elseif(!empty($produto->id)) {{ $produto->id }} @else {{ '' }} @endif" /><br>
            <div class="col-3">
                <label class="form-label">Nome</label><br>
                <input type="text" class="form-control" name="nome"
                    value="@if (!empty(old('nome'))) {{ old('nome') }} @elseif(!empty($produto->nome)) {{ $produto->nome }} @else {{ '' }} @endif" /><br>
            </div>
            <div class="col-3">
                <label class="form-label">Valor</label><br>
                <input type="text" class="form-control" name="valor"
                    value="@if (!empty(old('valor'))) {{ old('valor') }} @elseif(!empty($produto->valor)) {{ $produto->valor }} @else {{ '' }} @endif" /><br>
            </div>
            <div class="col-3">
                <label class="form-label">E-mail fornecedor</label><br>
                <input type="email" class="form-control" name="email"
                    value="@if (!empty(old('email'))) {{ old('email') }} @elseif(!empty($produto->email)) {{ $produto->email }} @else {{ '' }} @endif" /><br>
            </div>
            <div class="col-3">
                <label class="form-label">Categoria</label><br>
                <select name="categoria_id" class="form-select">
                    @foreach ($categorias as $item)
                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                    @endforeach
                </select>
            </div>
            @php
                $nome_imagem = !empty($produto->imagem) ? $produto->imagem : 'sem_imagem.jpg';
            @endphp
            <div class="col-6">
                <br>
                <img class="img-thumbnail" src="/storage/{{ $nome_imagem }}" width="300px" />
                <br><br>
                <input type="file" class="form-control" name="imagem" /><br>
            </div>
            <button class="btn btn-success" type="submit">
                <i class="fa-solid fa-save"></i> Salvar
            </button>
            <a href='{{ route('produto.index') }}' class="btn btn-primary"><i class="fa-solid fa-arrow-left"></i>
                Voltar</a> <br><br>
        </form>
    </div>
</div>
</div>
@endsection
