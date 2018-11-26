<?php

class Bairro extends Eloquent
{

	protected $table = 'bairro';
	
	public function GetBairro($id) {
		$bairro = DB::table('bairro')
			->select('bairro.NomeBairro', 'cidade.NomeCidade', 'estado.Sigla')	
			->where('bairro.id','=',$id)			
			->join('cidade', 'cidade.id', '=', 'bairro.idcidade')
			->join('estado', 'estado.ID', '=', 'cidade.Estado_ID')
			->first();						
			
			if ($bairro!=null) {			
				$ret = Lang::get('servicos.Bairro').': '.$bairro->NomeBairro.' - '.$bairro->NomeCidade.'/'.$bairro->Sigla;
			} else {
				$ret = '';
			}
			return $ret;
	}
	
	public function acento_para_html($umarray){
		$comacento = array('Á','á','Â','â','À','à','Ã','ã','É','é','Ê','ê','È','è','Ó','ó','Ô','ô','Ò','ò','Õ','õ','Í','í','Î','î','Ì','ì','Ú','ú','Û','û','Ù','ù','Ç','ç',);
		$acentohtml   = array('&Aacute;','&aacute;','&Acirc;','&acirc;','&Agrave;','&agrave;','&Atilde;','&atilde;','&Eacute;','&eacute;','&Ecirc;','&ecirc;','&Egrave;','&egrave;','&Oacute;','&oacute;','&Ocirc;','&ocirc;','&Ograve;','&ograve;','&Otilde;','&otilde;','&Iacute;','&iacute;','&Icirc;','&icirc;','&Igrave;','&igrave;','&Uacute;','&uacute;','&Ucirc;','&ucirc;','&Ugrave;','&ugrave;','&Ccedil;','&ccedil;');
		$umarray  = str_replace($comacento, $acentohtml, $umarray);
		return $umarray;
	}

}