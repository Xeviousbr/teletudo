<?php

class VlrtransfController extends BaseController {

    public function create()
    {
        $vlrtr = Pagamento::all();
        return View::make('vlrtransf.create')
            ->with('vlrtransf', $vlrtr);
    }

    public function store()
    {
        $input = Input::all();
        $sql = "Insert into vlrtransf (IdPagto, idConta, BCO, AGE, CTA, EMAIL) Values ( ";
        $sql = $sql.$input['txPagto'].', ';
        $sql = $sql.$input['txidConta'].', ';
        $sql = $sql.$input['BCO'].', ';
        $sql = $sql."'".$input['AGE']."', ";
        $sql = $sql."'".$input['CTA']."', ";
        $sql = $sql."'".$input['EMAIL']."')";

        DB::update($sql);

        // Obter o fornecedor do Pedido
        $qry = DB::table('pedidoItens')
            ->join('produtos', 'produtos.ID', '=', 'pedidoItens.idprod')
            ->select('produtos.Empresax_ID')
            ->where('pedidoItens.idPed', '=', $input['txPagto'])
            ->get();
        $ifForn = $qry[0]->Empresax_ID;

        $Teste=Session::get('Teste');
        $idEntrega = 0;
        $Valor = Session::get('VLRTOTAL');
        if ($Teste==1) {
            $idEntrega = 1;
            $sql="Update notificacao set vizualizado = null, Confirmado = null ";
        } else {
            $idEntrega = DB::table('entrega')->max('id');
            $idTransf = DB::table('vlrtransf')->max('id');
            $sql = "Insert into notificacao (idPedido, idFornec, idTransf, Valor, Hora) Values ( ";
            $sql = $sql.$input['txPagto'].', ';     // idPedido
            $sql = $sql.$ifForn.', ';               // idFornec
            $sql = $sql.$idTransf.', ';             // idTransf
            $sql = $sql.$Valor.', ';                // Valor
            $sql = $sql.'now())';                   // Hora
        }
        DB::update($sql);

        $coment = $input['txMensagem'];
        if ($coment>'') {
            DB::update("Update pedido set Comentario = '".$coment."' where idPed = ".$input['txPagto']);
        }

        return Redirect::to('/entrega/'.$idEntrega);
    }
}
