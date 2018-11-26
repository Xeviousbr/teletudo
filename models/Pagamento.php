<?php

class Pagamento extends Eloquent
{
    protected $table = 'pagamento';

    public function VePagamento($id) {
        $reg = DB::table('pagamento')->select(DB::raw('count(*) as Quant'))
            ->where('idPed','=',$id)
            ->first();
        if ($reg->Quant==0) {
            return '0';
        } else {
            DB::update("update pedido set status = 2 where idPed = " .$id);	
            return '1';
        }
    }

/*    private $IDTRANS = '';
    private $IDSERV = '';
    private $Valor = 0;
    private $DtLimPagto = '';
    private $ID = '';
    private $Abran = '';*/

/*    public function GetReg($id) {
        $this->ID = $id;
        $reg = DB::table('pagamento')
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

            $sql = 'Select date_add(now(), interval 31 day) "DtLimPagto"';
            $QryDt = DB::select($sql);
            foreach ($QryDt as $reg) {
                $this->DtLimPagto = $reg->DtLimPagto;
            }
            $this->IDTRANS=0;
            return 'Neste momento o anuncio foi renovado';
        } else {
            $this->Valor=$reg->Valor;
            $this->DtLimPagto=$reg->DtLimPagto;
            $this->IDTRANS=$reg->IDTRANS;
            $data = date('d/m/Y' , strtotime($reg->DtClicado));
            $ret = 'O anúncio foi renovado em '.$data;
            return $ret;
        }
    }*/

/*    public function RegistraVisita() {
        $sql = 'update pagamento';
        $sql = $sql.' set DtClicado = NOW(), ';
        $sql = $sql.' DtLimPagto = date_add(now(), interval 31 day), ';
        $sql = $sql.' Valor = '.$this->Valor;
        $sql = $sql.' ,IP = "'.$_SERVER['REMOTE_ADDR'].'" ';
        $sql = $sql.' Where id = '.$this->ID;
        DB::update($sql);
    }*/

/*    public function getIDTRANS() {
        return $this->IDTRANS;
    }*/

/*    public function getIDSERV() {
        return $this->IDSERV;
    }*/

/*    public function getNome() {
        $reg = DB::table('servicos')
            ->select('nome','abrangencia')
            ->where('id','=',$this->IDSERV)
            ->first();
        $this->Abran = $reg->abrangencia;
        return $reg->nome;
    }*/

/*    public function getValor() {
        return number_format ($this->Valor, 2, ',', '.');
    }*/

/*    public function getLimPag() {
        return $this->DtLimPagto;
    }*/

/*    public function GetPagamento() {
        $sql = 'SELECT pagamento.ID idpag, pagamento.IDSERV, pagamento.Valor, pagamento.DtClicado, pagamento.DtLimPagto ';
        $sql = $sql . ', servicos.nome, IFNULL(vlrtransf.ID,0) transfID, vlrtransf.Status ';
        $sql = $sql . ',IFNULL(vlrtransf.BCO,0) BCO ';
        $sql = $sql . ',IFNULL(vlrtransf.AGE,0) AGE ';
        $sql = $sql . ',IFNULL(vlrtransf.CTA,0) CTA ';
        $sql = $sql . ',IFNULL(vlrtransf.create_at,0) DT ';
        $sql = $sql . ',IFNULL(vlrtransf.EMAIL,0) EMAIL ';
        $sql = $sql . ',vlrtransf.Obs ';
        $sql = $sql . ',vlrtransf.DtAtualiz ';
        $sql = $sql . ',Now()-vlrtransf.DtAtualiz TmpViz ';
        $sql = $sql . 'From pagamento ';
        $sql = $sql . 'inner join servicos on servicos.id = pagamento.IDSERV ';
        $sql = $sql . 'and servicos.Cobrado is null ';
        $sql = $sql . 'and servicos.Cobrar = 1 ';
        $sql = $sql . 'and servicos.AvisadoPagto = 2 ';
        $sql = $sql . 'left join vlrtransf on vlrtransf.IdPagto = pagamento.ID and vlrtransf.Status < 2 ';
        $sql = $sql . 'Where pagamento.Quitado = 0 ';
        $sql = $sql . 'order by vlrtransf.DtConfirmacao desc, pagamento.DtClicado desc ';
        
        // echo $sql; die;
        
        $reg = DB::select( DB::raw($sql));
        return $reg;
    }*/
    
    /*
    
    - Quem passou mais de 30 dias da data que foi clicado e não pagou
      - Seta 2016 no campo renovado de serviços
      - Passa quitado para 2 no pago
      - Deve permitir ainda que o cara pague
      - Mas não deve continuar na lista como pendente
      
    - Se o cara passou mais de 30 dias e nem chegou a dar um clique
      - 
    
     private function VerificaClicados() {
	SELECT pagamento.IDSERV
	FROM pagamento
	INNER JOIN servicos
	    ON servicos.id = pagamento.IDSERV
	where pagamento.DtClicado < '2016-09-24'    
    } */
    
/*    public function getVizualizado($TmpViz, $DtAtualiz) {
        $tempo=date('d/m/Y H:m:s', $TmpViz);
        if (strpos($tempo,'01/01/1970')>-1) {
            $tempo = substr($tempo, 9);
            return $tempo;
        } else {
            return $DtAtualiz;
        }
        
    }*/

/*    public function getAbrangencia() {
        return $this->Abran.' Kms';
    }*/
    
/*    public function getQtdMaisQAno() {
	$qtd=0;
	$sql = 'SELECT Count(0) qtd ';
	$sql = $sql.'FROM servicos ';    
	$sql = $sql.'WHERE AvisadoPagto = 0 ';
	$sql = $sql.'and Cobrar = 1 ';
	$sql = $sql.'and inativo = 0 ';	        
	$sql = $sql.'and created_at < '.$this->DtHoje();
	$sql = $sql.'and Cobrado is null ';        
	$rgQtd = DB::select( DB::raw($sql));
    	foreach ($rgQtd as $reg) {
        	$qtd=$reg->qtd;
    	}	
	return $qtd;
    }*/
    
/*    private function DtHoje() {
    	$ano = date("Y");
    	$ano--; 
    	$dt = '"'.$ano.'-'.date("m").'-'.date("d").'" ';
    	return $dt;
    }*/
    
/*    public function getMaisQAno() {

    $sql = 'SELECT id, nome, email, face ';    
    // $sql = 'SELECT id, nome ';
        
    $sql = $sql.'FROM servicos ';
    $sql = $sql.'WHERE AvisadoPagto = 0 ';
    $sql = $sql.'and Cobrar = 1 ';
    $sql = $sql.'and inativo = 0 ';
        
	$sql = $sql.'and created_at < '.$this->DtHoje();
	// $sql = $sql.'and created_at < "2015-09-27" ';
	
	$sql = $sql.'and Cobrado is null ';
        
	// $sql = $sql.'and ( email > "" or face > "")';

    // echo $sql; die;

	$reg = DB::select( DB::raw($sql));	
	return $reg;
    }*/
    
/*    public function CriaRegistro($id, $op) {
        switch ($op) {
            case 1: // Cobrar
            {
                DB::update('update servicos set Cobrar = 1, AvisadoPagto = 1 Where ID = '.$id);
                DB::insert('insert into pagamento (IDSERV) values (?)', [$id]);
                break;
                
            }
            case 2: // Não Cobrar 
            {
                DB::update('update servicos set Cobrar = 0 Where ID = '.$id);
                break;
            }
            case 3: // Incobravel
            {
                DB::update('update servicos set Cobrar = 2 Where ID = '.$id);
                break;
            }
            case 4: // Já Cobrado
            {
                DB::update('update servicos set Cobrado = now() Where ID = '.$id);
                break;
            }
        }
    }*/

/*    public function QtdAvisar()
    {
        $sql = 'SELECT Count(0) qtd ';
        $sql = $sql.'from servicos ';
        $sql = $sql.'where AvisadoPagto = 1 ';
        $rgQtd = DB::select( DB::raw($sql));
        $qtd = 0;
        foreach ($rgQtd as $reg) {
            $qtd=$reg->qtd;
        }
        return $qtd;        
    }*/

/*    public function getAvisar()
    {
        $sql = 'Select servicos.id, servicos.nome, servicos.email, servicos.face, servicos.FaceContato, pagamento.id PagamentoID ';
        $sql = $sql.'from servicos ';
        $sql = $sql.'inner join pagamento on pagamento.IDSERV = servicos.id ';
        $sql = $sql.'where servicos.AvisadoPagto = 1 ';
        $reg = DB::select( DB::raw($sql));
        return $reg;
    }    */
        
/*    public function AtualizaM($id, $mail) {
        // echo 'update servicos set email = '.$mail.' Where ID = '.$id; die; 
	    DB::update('update servicos set email = "'.$mail.'" Where ID = '.$id);
    } */
    
/*    public function Informou($id) {
        // echo 'update servicos set AvisadoPagto = 2 Where ID = '.$id; die;
    	DB::update('update servicos set AvisadoPagto = 2 Where ID = '.$id);
    }                   */
    
/*    public function Incobravel($id) {
    	DB::update('update servicos set email = null, face = null, Cobrar = 2 Where ID = '.$id);
    	DB::update('delete from pagamento Where IDSERV = '.$id);
    }      */
    
}