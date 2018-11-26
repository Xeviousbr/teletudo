<?php

class ServicosController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// get all the servicos
		$servicos = Servicos::all();

		// load the view and pass the servicos
		return View::make('servicos.index')
			->with('servicos', $servicos);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		// load the create form (app/views/servicos/create.blade.php)
		return View::make('servicos.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
	
		echo 'entrou no store'; die;
	
		// validate
		// read more on validation at http://laravel.com/docs/validation
		$rules = array(
			'nome'       => 'required'
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::to('servicos/create')
				->withErrors($validator)
				->withInput(Input::except('password'));
		} else {
			// store
			$servico = new Servicos;
			$servico->name    = Input::get('nome');
			$servico->email = Input::get('email');
			$servico->youtube = Input::get('youtube');
			$servico->save();
			
			// redirect
			Session::flash('message', 'Successfully created nerd!');
			return Redirect::to('servicos');
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
		$servico = Servicos::find($id);

		// show the view and pass the nerd to it
		return View::make('servicos.show')
			->with('servico', $servico);
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
		$servico = Servicos::find($id);

		// show the edit form and pass the nerd
		return View::make('servicos.edit')
			->with('servico', $servico);
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
//		$rules = array(
//			'name'       => 'required'
//		);
//		$validator = Validator::make(Input::all(), $rules);

		// process the login
//		if ($validator->fails()) {
//			return Redirect::to('servicos/' . $id . '/edit')
//				->withErrors($validator)
//				->withInput(Input::except('password'));
//		} else {
			// store
			$servico = Servicos::find($id);			
			$servico->Texto = Input::get('Texto');			
			$servico->email = Input::get('email');			
			
			$servico->Fone = Input::get('Fone');
			$servico->Celula = Input::get('Celula');
			
			$servico->youtube = Input::get('youtube');			
			$servico->save();

			// redirect
			Session::flash('message', 'Atualiza&ccedil;&atilde;o realizada');
			// return Redirect::to('servicos');
			return Redirect::to('servicos/' . $servico->id);
			
		// }
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		// delete
		$servico = Servicos::find($id);
		$servico->delete();

		// redirect
		Session::flash('message', 'Successfully deleted the nerd!');
		return Redirect::to('servicos');
	}
	
	public function showLogin()
	{
		// show the form
		return View::make('login');
	}			
	

}