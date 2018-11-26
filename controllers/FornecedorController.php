<?php
class FornecedorController extends Controller {

    public function index()
    {
        return View::make('fornecedor.index');
    }

    public function create()
    {
        return View::make('fornecedor.create');
    }

    public function show($id)
    {
        $forn = Empresa::find($id);
        return View::make('fornecedor.show')
            ->with('empresa', $forn );
    }

    public function store() {
        $idUser = Auth::id();
        $sql = "select pessoa.Endereco_ID, pessoa.idCaptador, pessoa.user, ";
        $sql.="bairro.idcidade ";
        $sql.="from pessoa ";
        $sql.="inner join endereco on endereco.ID = pessoa.Endereco_ID ";
        $sql.="inner join bairro on bairro.id = endereco.idBairro ";
        $sql.="where pessoa.id = ".$idUser;
        $qry = DB::select( DB::raw($sql));
        $empr = new Empresa;
        $empr->idPessoa = $idUser;
        $empr->idcidade = $qry[0]->idcidade;
        $empr->idCaptador = $qry[0]->idCaptador;
        $empr->Empresa = Input::get('empresa');
        $empr->email = Input::get('email');
        $empr->idEndereco = $qry[0]->Endereco_ID;
        $empr->DiaAcerto = Input::get('DiaAcerto');
        $empr->Obs = Input::get('Obs');
        $empr->Telefone = Input::get('Telefone');
        $empr->categoriasempresas_ID = Input::get('TipoProd');
        $Tpe = Input::get('Tpe');
        switch ($Tpe) {
            case 1:
                // Sómente entrega própria
                $idEntrega = 0;
                break;
            case 2:
                // Sómente entrega do site
                $idEntrega = 1;
                break;
            case 3:
                // As duas formas de entregas
                $idEntrega = 2;
                break;
        }
        // Tipo
        $empr->idEntrega = $idEntrega;

        // Forma de cálculo da entrega
        // $empr->tpEntrega = $tpEntrega;

        // DistMax (habilitado só se for 1) Kms

        // EntregaFree

        // TempoEntrega (tempo que a mercadoria estará pronta para ser entregue ao motoboy)

        $empr->idEmpresa = DB::table('empresa')->max('idEmpresa')+1;
        // CONTINUAR A GRAVAÇÃO DO FORNECEDOR

        /*
        Campos autoescritos ((vir automaticamente e permitir alterar))

            idEndereco(se mudar tem permitir gravar o endereço)


        Tipo de Entrega
            Entrega Própria [x] Sim ou Não[ ]
            campo=>idEntrega[0 ou 1]

            Se vende por tele-entrega free(então tem que informar que cobramos 1 real por venda) */

        $empr->save();

        if ($qry[0]->user==null) {
            $user = Input::get('user');
            $passOrig = Input::get('senha');
            $pass = $pass = Hash::make($passOrig);;
            DB::update("update pessoa set user = '".$user."', password = '".$pass."' where id = ".$idUser );
        }
        // if ($Tpe==1) {
        // Se é entrega só pela play delivery então é
        return Redirect::to('/fornecedor');
        /* } else {
            return Redirect::to('confgentrega');
        }*/
    }

    public function confgentrega()
    {
        return View::make('fornecedor.confgentrega');
    }

}