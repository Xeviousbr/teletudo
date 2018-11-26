<?php
class PedidoController extends Controller {
	
	public function show($id)
	{
		$pedido = Pedido::where("idPed",$id)->first();
		return View::make('pedido.show')
			->with('pedido', $pedido);
	}	
	
	public function Aciona() {
            return View::make('pedido.index');
	}

    public function Resumo()
    {
        return View::make('pedido.resumo');
    }

    /*public function loginfb()
    {
        return View::make('pedido.loginfb');
    }*/

    public function Pagtodireto()
    {
        return View::make('pedido.pagtodireto');
    }

    public function Criapedido()
    {
        return View::make('pedido.criapedido');
    }

    public function PosFace()
    {
        return View::make('pedido.posface');
    }

    public function Captador()
    {
        return View::make('pedido.captador');
    }

    public function Convite()
    {
        return View::make('pedido.convite');
    }

    public function Logarede()
    {
        return View::make('pedido.logarede');
    }

    public function confgentrega()
    {
        return View::make('pedido.confgentrega');
    }


}