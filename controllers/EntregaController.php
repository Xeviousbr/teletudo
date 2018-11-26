<?php

class EntregaController extends BaseController {

    public function Aciona() {
        // passar o id da entrega
        return View::make('entrega.show');
    }
    
	public function show($id)
	{
		$entrega = Entrega::find($id);
		return View::make('entrega.show')
			->with('entrega', $entrega);
	}
    
    public function create()
    {
        $entrega = Entrega::all();

        // echo 'create'; die;

        return View::make('entrega.create')
            ->with('entrega', $entrega);
    }
    
    public function Processa()
    {
    	echo 'AQUI(Processa)'; die;
        return View::make('entrega.Processa');
    }
    
    public function Propria() 
    {
    	$entrega = Entrega::find(1);
 	return View::make('entrega.resumo')
		->with('entrega', $entrega);   	
    }

    public function store() {
        echo 'Entrega.Store'; die;
    }

}