<?php

class Renovar extends Eloquent
{
    protected $table = 'pagar';
    private $IDTRANS = '';
    private $IDSERV = '';
    private $Valor = 0;
    private $DtLimPagto = '';
    private $ID = '';
    private $novo = '0';

    public function GetReg($id) {
        $this->ID = $id;
        $reg = DB::table('pagar')
            ->select('IDTRANS', 'IDSERV', 'Valor','DtLimPagto','DtClicado')
            ->where('id','=',$id)
            ->first();
        $this->IDSERV=$reg->IDSERV;
        if ($reg->DtClicado == null) {

            $sql = 'SELECT  CASE 
                      WHEN clicks<20 THEN 10  
                      WHEN clicks<40 THEN 20  
                      WHEN clicks<75 THEN 30  
                      WHEN clicks<100 THEN 40  	
                      ELSE 50 
                    END AS "Valor" 
                    FROM servicos 
                    WHERE id = '.$this->IDSERV;
            $Qryservs = DB::select($sql);
            foreach ($Qryservs as $reg) {
                $this->Valor = $reg->Valor;
            }

            $sql = 'update pagar';
            $sql = $sql.' set DtClicado = NOW(), ';
            $sql = $sql.' DtLimPagto = date_add(now(), interval 31 day), ';
            $sql = $sql.' Valor = '.$this->Valor;
            $sql = $sql.' Where id = '.$this->ID;
            DB::update($sql);

            $sql = 'Select date_add(now(), interval 31 day) "DtLimPagto"';
            $QryDt = DB::select($sql);
            foreach ($QryDt as $reg) {
                $this->DtLimPagto = $reg->DtLimPagto;
            }
            $this->IDTRANS=0;
            $this->novo = '1';
        } else {
            $this->Valor=$reg->Valor;
            $this->DtLimPagto=$reg->DtLimPagto;
            $this->IDTRANS=$reg->IDTRANS;
            
	    $this->novo = '0';
            // return 0;
	    // return '1';
        }
    }

    public function getIDTRANS() {
        return $this->IDTRANS;
    }

    public function getIDSERV() {
        return $this->IDSERV;
    }

    public function getNome() {
        $reg = DB::table('servicos')
            ->select('nome')
            ->where('id','=',$this->IDSERV)
            ->first();
        return $reg->nome;
    }
    
    public function getValor() {
        // $valor = number_format ($this->Valor, 2);
        // $valor = str_replace($valor, '.', ','); 
        // return $valor;
        return number_format ($this->Valor, 2, ',', '.');
    }
    
    public function getLimPag() {
        return $this->DtLimPagto;
    }

    public function GetPagar() {
        $sql = 'SELECT pagar.*, servicos.nome, IFNULL(vlrtransf.ID,0) transfID, vlrtransf.Status ';
        $sql = $sql . ',IFNULL(vlrtransf.BCO,0) BCO ';
        $sql = $sql . ',IFNULL(vlrtransf.AGE,0) AGE ';
        $sql = $sql . ',IFNULL(vlrtransf.CTA,0) CTA ';
        $sql = $sql . ',IFNULL(vlrtransf.create_at,0) DT ';
        $sql = $sql . ',IFNULL(vlrtransf.EMAIL,0) EMAIL ';
        $sql = $sql . ',vlrtransf.Obs ';
        $sql = $sql . ',vlrtransf.DtAtualiz ';
        $sql = $sql . ',Now()-vlrtransf.DtAtualiz TmpViz ';
        $sql = $sql . 'From pagar ';
        $sql = $sql . 'inner join servicos on servicos.id = pagar.IDSERV ';
        $sql = $sql . 'left join vlrtransf on vlrtransf.IdPagto = pagar.ID and vlrtransf.Status < 2 ';
        $sql = $sql . 'Where pagar.Quitado = 0 ';
        $reg = DB::select( DB::raw($sql));
        return $reg;
    }
    
    public function getVizualizado($TmpViz, $DtAtualiz) {
        $tempo=date('d/m/Y H:m:s', $TmpViz);
        if (strpos($tempo,'01/01/1970')>-1) {
            $tempo = substr($tempo, 9);
            return $tempo;
        } else {
            return $DtAtualiz;
        }
        
    }
    
    public function GetNovo() {
    	return $this->novo;
    }        
    
}