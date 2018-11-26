<?php
class ContasbancariasController extends Controller {
	
	public function Aciona() {
		 return View::make('contasBancarias.index');
	}

    public function store()
    {
        $nmBanco = "'%".Input::get('cbBanco')."'";
        // $nmBanco = "'".Input::get('cbBanco')."'";


        $sql = "select cod from bancos where banco like ".$nmBanco." or apelido like '".Input::get('cbBanco')."'";
        // $sql = "select cod from bancosx where banco = ".$nmBanco." or apelido = '".Input::get('cbBanco')."'";
        $qry = DB::select( DB::raw($sql));
        $conban = new Contasbancarias;
        $conban->idBanco = $qry[0]->cod;
        $conban->Agencia = Input::get('txAgencia');
        $conban->Conta = Input::get('txConta');
        $conban->idEmpresa = Input::get('txEmpresa');
        $conban->save();
        ?>
        <script>
            document.location.assign('http://www.tele-tudo.com/contasbancarias');
        </script>
        <?php
        // return Redirect::to('contasbancarias');
    }

    public function index()
    {
        $conban = Contasbancarias::all();
        return View::make('contasbancarias.index')
            ->with('contasbancarias', $conban);
    }

    public function create()
    {
        return View::make('contasbancarias.create');
    }

    public function destroy($id)
    {
        $conban = Produtos::find($id);
        $conban->delete();
        Session::flash('message', 'Successfully deleted');
        return Redirect::to('produtos');
    }
}