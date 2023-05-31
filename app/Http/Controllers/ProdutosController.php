<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    function index()
    {
        $Produtos = Produto::All();
        // dd($Produtos);

        return view('<ProdutosList')->with(['Produtos' => $Produtos]);
    }

    function create()
    {
        $categorias = Categoria::orderBy('nome')->get();
        //dd($categorias);
        return view('ProdutosForm')->with(['categorias' => $categorias]);
    }

    function store(Request $request)
    {
        $request->validate(
            Produto::rules(),
            Produto::messages()
        );

        //adiciono os dados do formulário ao vetor
        $dados = [
            'nome' => $request->nome,
            'telefone' => $request->telefone,
            'email' => $request->email,
            'categoria_id' => $request->categoria_id,
        ];

        $imagem = $request->file('imagem');
        $nome_arquivo = '';
        //verifica se o campo imagem foi passado uma imagem
        if ($imagem) {
            $nome_arquivo = date('YmdHis') . '.' . $imagem->getClientOriginalExtension();

            $diretorio = 'imagem/';
            //salva a imagem em uma pasta
            $imagem->storeAs($diretorio, $nome_arquivo, 'public');
            //adiciona ao vetor o diretorio do arquivo e o nome
            $dados['imagem'] = $diretorio . $nome_arquivo;
        }

        //dd( $request->nome);
        //passa o vetor com os dados do formulário como parametro para ser salvo
        Produto::create($dados);

        return \redirect('Produto')->with('success', 'Cadastrado com sucesso!');
    }

    function edit($id)
    {
        //select * from Produto where id = $id;
        $produto = Produto::findOrFail($id);
        //dd($Produto);
        $categorias = Categoria::orderBy('nome')->get();

        return view('ProdutosForm')->with([
            'produto' => $produto,
            'categorias' => $categorias,
        ]);
    }

    function show($id)
    {
        //select * from Produto where id = $id;
        $Produto = Produto::findOrFail($id);
        //dd($Produto);
        $categorias = Categoria::orderBy('nome')->get();

        return view('ProdutosForm')->with([
            'produto' => $produto,
            'categorias' => $categorias,
        ]);
    }

    function update(Request $request)
    {
        //dd( $request->nome);
        $request->validate(
            Produto::rules(),
            Produto::messages()
        );

        //adiciono os dados do formulário ao vetor
        $dados =  [
            'nome' => $request->nome,
            'telefone' => $request->telefone,
            'email' => $request->email,
            'categoria_id' => $request->categoria_id,
        ];

        $imagem = $request->file('imagem');
        //verifica se o campo imagem foi passado uma imagem
        if ($imagem) {
            $nome_arquivo = date('YmdHis') . '.' . $imagem->getClientOriginalExtension();

            $diretorio = 'imagem/';
            //salva a imagem em uma pasta
            $imagem->storeAs($diretorio, $nome_arquivo, 'public');
            //adiciona ao vetor o diretorio do arquivo e o nome
            $dados['imagem'] = $diretorio . $nome_arquivo;
        }

        //metodo para atualizar passando o vetor com os dados do form e o id
        Produto::updateOrCreate(
            ['id' => $request->id],
            $dados
        );

        return \redirect('Produto')->with('success', 'Atualizado com sucesso!');
    }

    function destroy($id)
    {
        $Produto = Produto::findOrFail($id);

        //verifica se existe o arquivo vinculado ao registro e depois remove
        if (Storage::disk('public')->exists($Produto->imagem)) {
            Storage::disk('public')->delete($Produto->imagem);
        }
        $Produto->delete();

        return \redirect('Produto')->with('success', 'Removido com sucesso!');
    }

    function search(Request $request)
    {
        if ($request->campo == 'nome') {
            $Produtos = Produto::where(
                'nome',
                'like',
                '%' . $request->valor . '%'
            )->get();
        } else {
            $Produtos = Produto::all();
        }

        //dd($Produtos);
        return view('ProdutosList')->with(['Produtos' => $Produtos]);
    }
}
