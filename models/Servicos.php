<?php

class Servicos extends Eloquent
{
	protected $table = 'servicos';
	private $TemLocal = 0;
		
	public function GetServs($cep, $cat, $lat, $long, $idPais, $pag){

        if ($lat=='') {
			if ($cep=='') {
				$this->TemLocal = 0;
			} else {
				$this->TemLocal = 1;
			}
            $sql = 'select id, nome, imagem, dadoslang.banner ';
            $sql = $sql.' from servicos ';
            $sql = $sql.' left join dadoslang on dadoslang.idServ = servicos.id and dadoslang.SgLng = "'.App::getLocale().'" ';
            $sql = $sql.' where inativo = 0 ';
            if ($cat>'') {
                $sql = $sql.' and categoria = '.$cat;
            }
            $sql = $sql.' and ((nacional = 1 and pais = '.$idPais.') or pais = 0 ) ';

            $sql = $sql.' order by destaque desc, clicks desc ';
		} else {
			if (Auth::check()) {
				/* echo 'GetServs.COM COORDENADAS</p>';
			 	echo 'lat = '.$lat.'</p>';
			 	echo 'long = '.$long.'</p>';
			 	echo 'Cat = '.$cat.'</p>'; */
			}

		            $sql = 'select distinct id, nome, imagem, distancia, banner ';
		            $sql =$sql.'from ( ';
		            $sql =$sql.'select id, nome, distancia, imagem, (distancia<abrangencia) dentro, banner ';
		            $sql =$sql.'from ( ';
		            $sql =$sql.'select id, nome, abrangencia, imagem, fn_distance('.$lat.', '.$long.', servicos.lat, servicos.lon) distancia, dadoslang.banner ';
		            $sql =$sql.'from servicos  ';
		            $sql = $sql.' left join dadoslang on dadoslang.idServ = servicos.id and dadoslang.SgLng = "'.App::getLocale().'" ';
		            $sql =$sql.'where inativo = 0 ';
		            $sql =$sql.'and (pais = '.$idPais.' or pais = 0 ) ';
		            if ($cat>'') {
		                $sql = $sql.' and categoria = '.$cat;
		            }
		            $sql =$sql.') X  ';
		            $sql =$sql.'having dentro = 1 ';
		            $sql =$sql.'union  ';
		            $sql =$sql.'select id, nome, fn_distance('.$lat.', '.$long.', servicos.lat, servicos.lon) distancia , ';
		            $sql =$sql.'imagem, 0 as dentro, dadoslang.banner ';
		            $sql =$sql.'from servicos  ';
		            $sql = $sql.' left join dadoslang on dadoslang.idServ = servicos.id and dadoslang.SgLng = "'.App::getLocale().'" ';
		            $sql =$sql.'where inativo = 0 ';
		            $sql =$sql.'and ((nacional = 1 and pais = '.$idPais.') or pais = 0 ) ';
		            if ($cat>'') {
		                $sql = $sql.' and categoria = '.$cat;
		            }
		            $sql =$sql.') xx ';
		            $sql =$sql.'order by distancia ';
					
		}				
				
		$sql = $sql.' limit 11 OFFSET '.$pag.'0 ';

		$servs = DB::select($sql);

        // echo $sql; die;
		
		return $servs;
	}
	
	public function MarcaClick($id) {
		DB::update('update servicos set clicks = clicks + 1, clicado = NOW() where id = '.$id);		
		Session::put('SERV', $id);
	}

	public function MarcaCliLista($ids) {
		// echo '</Br>update servicos set clicks = clicks + 1 where id in ('.$ids.')';
		DB::update('update servicos set ClickLista = ClickLista + 1 where id in ('.$ids.')');
	}

    public function ProcuraServico($texto, $lat, $long, $idPesq) {
        $ret=0;
        $Cons = DB::table('categorias_servicos')
            ->select('id')
            ->where('descricao', 'like', $texto)
            ->first();
        if ($Cons!=null) {
            $sql = 'select Count(0) as Quant ';
            $sql.='from ( ';
            $sql.='SELECT id, fn_distance('.$lat.', '.$long.', servicos.lat, servicos.lon) distancia, abrangencia ';
            $sql.='FROM servicos ';
            $sql.='WHERE categoria = '.$Cons->id.' ';
            $sql.=') X ';
            $sql.='Where distancia < abrangencia ';
            $qry = DB::select( DB::raw($sql));
            if ($qry!=null) {
                if ($qry[0]->Quant>0) {
                    DB::update("update procuras set encontrado = 3 where id = ".$idPesq );
                    $ret=$Cons->id;
                }
            }
        }
        return $ret;
    }

}