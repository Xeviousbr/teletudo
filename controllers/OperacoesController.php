<?php
class OperacoesController extends Controller {

//	protected function setupLayout()
//	{
//		if ( ! is_null($this->layout))
//		{
//			$this->layout = View::make($this->layout);
//		}
//	}
	
	public function Aciona() {
		 return View::make('operacoes.index');
	}

    // isto não funciona
    public function teste() {
        return View::make('operacoes.teste.php');
    }

    public function moip() {
        return View::make('operacoes.moip.php');
    }

}