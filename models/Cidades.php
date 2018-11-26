<?php

class Cidades extends Eloquent
{

	protected $table = 'cidade';		
	
	public function GetCidades($id) {
		$cidade = DB::table('cidade')
			->select('cidade.NomeCidade', 'estado.Sigla','pais.Nome')	
			->where('cidade.id','=',$id)			
			->join('estado', 'estado.ID', '=', 'cidade.Estado_ID')
			->join('pais', 'pais.ID', '=', 'estado.idPais')
			->first();
			
			$sigla = '';
			if ($cidade->Sigla == null) {
				$sigla = $cidade->Nome;
			} else {
				$sigla = $cidade->Sigla;
			}		
			$ret = 'Localidade: '.$this->acento_para_html($cidade->NomeCidade).'/'.$sigla;		
			return $ret;
			
	}
	
	private function acento_para_html($umarray){
		$comacento = array('Á','á','Â','â','À','à','Ã','ã','É','é','Ê','ê','È','è','Ó','ó','Ô','ô','Ò','ò','Õ','õ','Í','í','Î','î','Ì','ì','Ú','ú','Û','û','Ù','ù','Ç','ç',);
		$acentohtml   = array('&Aacute;','&aacute;','&Acirc;','&acirc;','&Agrave;','&agrave;','&Atilde;','&atilde;','&Eacute;','&eacute;','&Ecirc;','&ecirc;','&Egrave;','&egrave;','&Oacute;','&oacute;','&Ocirc;','&ocirc;','&Ograve;','&ograve;','&Otilde;','&otilde;','&Iacute;','&iacute;','&Icirc;','&icirc;','&Igrave;','&igrave;','&Uacute;','&uacute;','&Ucirc;','&ucirc;','&Ugrave;','&ugrave;','&Ccedil;','&ccedil;');
		$umarray  = str_replace($comacento, $acentohtml, $umarray);
		return $umarray;
	}
	
	public function QuantAnuncios($id) {		        	
		$qry_cid = DB::table('bairro')
			->select('id')	
			->where('idcidade','=',$id)			
			->first();							        	
		if ($qry_cid != null) {
			$sql = 'select Count(*) qtd ';
			$sql = $sql .'FROM servicos ';
			$sql = $sql .'inner join bairro on bairro.id = servicos.Bairro_ID ';
			$sql = $sql .'where Bairro_ID in ( ';
			$sql = $sql .'select id from bairro where idcidade = '.$id.' ) ';			
		} else {
			$sql = 'select Count(*) qtd ';
			$sql = $sql .'FROM servicos ';
			$sql = $sql .'where Cidade_ID = '.$id;
		}
		// if (Auth::check()) { echo $sql.'</p>'; }
		$qry_qtd = DB::select( DB::raw($sql));
		$quant = 0;
		foreach ($qry_qtd as $var) { 
			$quant = $var->qtd;
			break;
		}		
		return $quant;
	}
	
	public function QuantMaxAnun($id) {
		$Conscidade = DB::table('cidade')
			->select('Milhao')	
			->where('cidade.id','=',$id)			
			->first();							
		return 5+($Conscidade->Milhao * 5);
	}

}