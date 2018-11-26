<?php

class Entregadoras extends Eloquent
{
	protected $table = 'entregadoras';
	
	public function GetUrl($id) {
		$entrega = DB::table('entregadoras')
		          ->select('Site')       
		          ->where('id','=',$id)
		          ->first();          													          
		if ($entrega != null) {
			return $entrega->Site;
		} else {
			return '';
		}
	}		
}