<?php

class CidadesEntregas extends Eloquent
{
	protected $table = 'cidades_entregas';

	// Chamado de servicos/show.blade.php
	public function GetCidadeEntrega($idCidade, $idBairro) {	
		
	  /* if (Auth::check()) { 
		  echo 'public function GetCidadeEntrega('.$idCidade,', '.$idBairro.')</p>'; 
	  }	*/					          							
		
		$ret = '';
		if ($idCidade == null) {				
						
			// SITUA��O NORMAL INFORMANDO O BAIRRO
			$bairros = DB::table('bairro')
			          ->select('idcidade')       
			          ->where('id','=',$idBairro)
			          ->first();          													          
			
			if ($bairros!=null) {			
				$idCidade = $bairros->idcidade;
			} else {
				$idCidade = 0;
			}
									
			/* $sql = 'select idcidade from bairro where id = '.$idBairro;
			$bairros = DB::select( DB::raw($sql));
			foreach ($bairros as $bairro) { 		
				$idCidade = $bairro->idcidade;				
			} */
		}			
		if ($idCidade>0) {
			$entrega = DB::table('cidades_entregas')
			          ->select('entregadora')       
			          ->where('cidade','=',$idCidade)
			          ->first();          											          
			          
			if ($entrega != null) {
								
				// H� ENTREGA PARA AQUELA CIDADE		
				$ret = $entrega->entregadora;
			} else {
				
//			  if (Auth::check()) { 
//				  echo 'N�O H� ENTREGA PARA AQUELA CIDADE'.'</p>';
//			  }						          								
				
				// N�O H� ENTREGA PARA AQUELA CIDADE						
				$cidades = DB::table('cidade')
				          ->select('Estado_ID')       
				          ->where('ID','=',$idCidade)
				          ->first();
				$entES = DB::table('entregadoras')
				          ->select('ID')       
				          ->where('idEstado','=',$cidades->Estado_ID)
				          ->first();          											
				if ($entES!=null) {
					// TEM ENTREGA PARA O ESTADO
					$ret = $entES->ID;
				}		         
			}
		}
						
		return $ret;
	}

}