<?php

class Clientes extends Eloquent
{
    protected $table = 'clientes';
    private $idCliente = 0;

    /* public function getSaldo ($idCliente) {
        $this->idCliente=$idCliente;
        $Cliente = DB::table('clientes')
                  ->select('Saldo')
                  ->where('idCliente','=',$idCliente)
                  ->first();
        return $Cliente->Saldo;
    } */

    public function getIdCliente($idPessoa)
    {
        $cliente = DB::table('clientes')
            ->select('IdCliente')
            ->where('idPessoa','=',$idPessoa)
            ->first();
        if ($cliente!=null) {
            return $cliente->IdCliente;
        } else {
            return 0;
        }
    }

    public function EnderOK ($idUser) {
        $qry = DB::table('pessoa')
            ->select('logradouro.adic')
            ->join('endereco', 'endereco.ID', '=', 'pessoa.Endereco_ID')
            ->join('logradouro', 'logradouro.ID', '=', 'endereco.Logradouro_ID')
            ->where('pessoa.id', '=', $idUser)
            ->first();
        if ($qry->adic==1) {
            return 0;
        } else {
            return 1;
        }
    }

    public function getBairrosCidadeCliente($idUser) {
        $Cons = DB::table('pessoa')
            ->select('bairro.NomeBairro','bairro.id')
            ->join('endereco', 'endereco.ID', '=', 'pessoa.Endereco_ID')
            ->join('logradouro', 'logradouro.ID', '=', 'endereco.Logradouro_ID')
            ->join('bairro', 'bairro.idcidade', '=', 'logradouro.Cidade_ID')
            ->where('pessoa.id','=',$idUser)
            ->orderBy('bairro.NomeBairro')
            ->get();
        $ret = "<option value='0'>Escolha</option>";
        $i=0;
        foreach ($Cons as $reg) {
            $ret.="<option value='".$reg->id."'>".$reg->NomeBairro."</option>";
            $i++;
        }
        return $ret;
    }


}