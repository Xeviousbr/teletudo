<?php

class ContasBancarias extends Eloquent
{
    protected $table = 'contasbancarias';

    /*public function Aciona() {
        return View::make('contasbancarias.index');
    }*/

    public function Contas($idEmpresa) {
        $qry = DB::table('contasbancarias')
            ->join('bancos', 'bancos.cod', '=', 'contasbancarias.idBanco')
            ->select('contasbancarias.id','contasbancarias.idBanco', 'bancos.banco','contasbancarias.Agencia','contasbancarias.Conta')
            ->where('contasbancarias.idEmpresa', '=', $idEmpresa)
            ->get();
        return $qry;
    }

    public function nmEmpresa($idEmpresa) {
        $qry = DB::table('empresa')
            ->select('Empresa')
            ->where('idEmpresa', '=', $idEmpresa)
            ->first();
        return $qry->Empresa;
    }

    public function Bancos() {
        $sql = "select * from (
           select cod, banco
           from bancos
           UNION
           select cod, apelido as banco
           from bancos
           where apelido > '' 
        ) x
        order by banco";
        $qry = DB::select( DB::raw($sql));
        /*$qry = DB::table('bancos')
            ->select('banco')
            ->orderBy('banco')
            ->get();*/
        return $qry;
    }

    public function Atualiza($id, $idBanco, $Agencia, $Conta) {
        DB::update("update contasbancarias set idBanco = ".$idBanco.", Agencia = '".$Agencia."', Conta = '".$Conta."', updated_at=now() Where id = ".$id);
    }

}