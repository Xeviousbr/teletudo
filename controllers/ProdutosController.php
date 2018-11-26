<?php

class ProdutosController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// get all the produtos
		$produtos = Produtos::all();

		// load the view and pass the produtos
		return View::make('produtos.index')
			->with('produtos', $produtos);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('produtos.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		// validate
		// read more on validation at http://laravel.com/docs/validation
		$rules = array(
			'nome'       => 'required'
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::to('produtos/create')
				->withErrors($validator)
				->withInput(Input::except('password'));
		} else {
			$produto = new Produtos;
			$produto->nome       = Input::get('nome');
			$produto->valor = Input::get('preco');
			$produto->Empresax_ID = Input::get('Empresa');
			$produto->imagem = Input::get('tximagem');
			$produto->descricao= Input::get('desc');
            $produto->CategoriasProdutos_ID = Input::get('cbCat');
			$produto->save();

			// redirect
			Session::flash('message', 'Produto adicionado com sucesso');

            $pag = Input::get('pag');
			if ($pag>1) {
                return Redirect::to('Cadastro?pag='.$pag);
            } else {
                return Redirect::to('Cadastro');
            }
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		// get the nerd
		$produto = Produtos::find($id);

		// show the view and pass the nerd to it
		return View::make('produtos.show')
			->with('produto', $produto);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		// get the nerd
		$produto = Produtos::find($id);

		// show the edit form and pass the nerd
		return View::make('produtos.edit')
			->with('produto', $produto);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		// validate
		// read more on validation at http://laravel.com/docs/validation
		$rules = array(
			'name'       => 'required'
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::to('produtos/' . $id . '/edit')
				->withErrors($validator)
				->withInput(Input::except('password'));
		} else {
			// store
			$produto = Produtos::find($id);
			$produto->nome       = Input::get('nome');
			$produto->save();

			// redirect
			Session::flash('message', 'Successfully updated nerd!');
			return Redirect::to('produtos');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
	    // echo 'aqui'; die;
		$produto = Produtos::find($id);
		$produto->delete();
		return Redirect::to('Cadastro');
	}
	
	public function sedex() {
		 return View::make('produtos.sedex');
		 // return View::make('contatos/index');
		 // return View::make('pages.contato');
		 
	}

    public function cadastro()
    {
        return View::make('produtos.cadastro');
    }

}