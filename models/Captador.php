<?php
class Captador
{
    protected $table = 'captador';

    public function getSaldo($idPessoa) {
        $qry = DB::table('conta')
            ->select('Pendente') // Saldo
            ->where('idPessoa','=',$idPessoa)
            ->first();
        if ($qry==null) {
            return 0;
        } else {
            return $qry->Pendente;
        }
    }

    public function getNomeConvite($id) {
        $qry = DB::table('pessoa')
            ->select('Nome')
            ->where('id','=',$id)
            ->first();
        return $qry->Nome;
    }

    public function getIdCaptador($idPessoa) {
        $qryC = DB::table('captador')
            ->select('idCaptador')
            ->where('idPessoa','=',$idPessoa)
            ->first();
        if ($qryC!=null) {
            return $qryC->idCaptador;
        } else {
            $idSup = 0;
            $qryP = DB::table('pessoa')
                ->select('idCaptador')
                ->where('id','=',$idPessoa)
                ->first();
            if ($qryP->idCaptador!=null) {
                $idSup = $qryP->idCaptador;
            }
            DB::update("insert into captador (idPessoa, idSup) values (".$idPessoa.", ".$idSup.")");
            return DB::table('captador')->max('idCaptador');
        }
    }

    public function getCaptados($IdCaptador) {
        $Qry = DB::table('pessoa')->select(DB::raw('count(*) as Quant'))
            ->where('idCaptador','=',$IdCaptador)
            ->first();
        return $Qry->Quant;
    }

    public function getQtqEquipe($IdCaptador) {
        return 0;
    }
}