<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fornecedor;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;

class FornecedorController extends Controller
{
    function index()
    {
        $fornecedor = Fornecedor::All();
        // dd($fornecedor);

        return view('fornecedorList')->with(['fornecedor' => $fornecedor]);
    }

    function create()
    {
        $categorias = Categoria::orderBy('nome')->get();
        //dd($categorias);
        return view('fornecedorForm')->with(['categorias' => $categorias]);
    }

    function store(Request $request)
    {
        $request->validate(
            Fornecedor::rules(),
            Fornecedor::messages()
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
        Fornecedor::create($dados);

        return \redirect('Fornecedor')->with('success', 'Cadastrado com sucesso!');
    }

    function edit($id)
    {
        //select * from Fornecedor where id = $id;
        $fornecedor = Fornecedor::findOrFail($id);
        //dd($Fornecedor);
        $categorias = Categoria::orderBy('nome')->get();

        return view('fornecedorForm')->with([
            'fornecedor' => $fornecedor,
            'categorias' => $categorias,
        ]);
    }

    function show($id)
    {
        //select * from Fornecedor where id = $id;
        $Fornecedor = Fornecedor::findOrFail($id);
        //dd($Fornecedor);
        $categorias = Categoria::orderBy('nome')->get();

        return view('fornecedorForm')->with([

            'fornecedor' => $fornecedor,
            'categorias' => $categorias,
        ]);
    }

    function update(Request $request)
    {
        //dd( $request->nome);
        $request->validate(
            Fornecedor::rules(),
            Fornecedor::messages()
        );

        //adiciono os dados do formulário ao vetor
        $dados =  [
            'nome' => $request->nome,
            'telefone' => $request->telefone,
            'email' => $request->email,
            //'categoria_id' => $request->categoria_id,
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
        Fornecedor::updateOrCreate(
            ['id' => $request->id],
            $dados
        );

        return \redirect('Fornecedor')->with('success', 'Atualizado com sucesso!');
    }

    function destroy($id)
    {
        $Fornecedor = Fornecedor::findOrFail($id);

        //verifica se existe o arquivo vinculado ao registro e depois remove
        if (Storage::disk('public')->exists($Fornecedor->imagem)) {
            Storage::disk('public')->delete($Fornecedor->imagem);
        }
        $fornecedor->delete();

        return \redirect('Fornecedor')->with('success', 'Removido com sucesso!');
    }

    function search(Request $request)
    {
        if ($request->campo == 'nome') {
            $fornecedor = Fornecedor::where(
                'nome',
                'like',
                '%' . $request->valor . '%'
            )->get();
        } else {
            $fornecedor = Fornecedor::all();
        }

        //dd($fornecedor);
        return view('fornecedorList')->with(['fornecedor' => $fornecedor]);
    }
}
