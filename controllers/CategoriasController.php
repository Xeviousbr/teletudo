<?php

class EnderecosController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// get all the Enderecos
		$Enderecos = Enderecos::all();

		// load the view and pass the Enderecos
		return View::make('Enderecos.index')
			->with('Enderecos', $Enderecos);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('Enderecos.create');
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
			return Redirect::to('Enderecos/create')
				->withErrors($validator)
				->withInput(Input::except('password'));
		} else {
			// store
			$Endereco = new Enderecos;
			$Endereco->nome       = Input::get('nome');
			$Endereco->save();

			// redirect
			Session::flash('message', 'Successfully created nerd!');
			return Redirect::to('Enderecos');
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
		$Endereco = Enderecos::find($id);

		// show the view and pass the nerd to it
		return View::make('Enderecos.show')
			->with('Endereco', $Endereco);
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
		$Endereco = Enderecos::find($id);

		// show the edit form and pass the nerd
		return View::make('Enderecos.edit')
			->with('Endereco', $Endereco);
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
			return Redirect::to('Enderecos/' . $id . '/edit')
				->withErrors($validator)
				->withInput(Input::except('password'));
		} else {
			// store
			$Endereco = Enderecos::find($id);
			$Endereco->nome       = Input::get('nome');
			$Endereco->save();

			// redirect
			Session::flash('message', 'Successfully updated nerd!');
			return Redirect::to('Enderecos');
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
		// delete
		$Endereco = Enderecos::find($id);
		$Endereco->delete();

		// redirect
		Session::flash('message', 'Successfully deleted the nerd!');
		return Redirect::to('Enderecos');
	}

}