<?php

class Categorias extends Eloquent
{

    protected $table = 'categorias';
    private $Obs = '';
    private $old_id = 0;
    private $old_desc = '';
    private $TemDentro = 0;
    private $UsaTele = 0;

    public function GetCategos($cep, $cat, $lat, $long, $idPais, $debug, $ResultadoGoogle){
        $idsServ = '';
        $tpBtn = 'btn btn-default';

        // echo 'idPais = '.$idPais.'</p>';
        $nrCep="0";
        if ($cep>'') {
            $nrCep = $cep;
        }

        if ($lat=='') {
            // echo 'GetCategos.SEM COORDENADAS</p>';

            // ALTERAR POSTERIORMENTE, PARA MOSTRAR INTERNACIONAL, QUANDO FOR

            if ($ResultadoGoogle=="OK") {
                $this->TemDentro = 1;
            } else {
                $this->TemDentro = 0;
            }

            $sql = 'select categorias_servicos.id, LangCat.Termo descricao ';
            $sql =$sql.' from pais ';
            $sql =$sql.' inner join Lang on Lang.IdLang = pais.idLang ';
            $sql =$sql.' inner join LangCat on LangCat.Lang = Lang.IdLang ';
            $sql =$sql.' inner join categorias_servicos on categorias_servicos.id = LangCat.idCat ';
            $sql =$sql.' where pais.ID = '.$idPais;
            $sql =$sql.'             and categorias_servicos.id in ';
            $sql =$sql.'             ( ';
            $sql =$sql.'                 SELECT distinct categoria ';
            $sql =$sql.'         FROM servicos ';
            $sql =$sql.'         WHERE inativo = 0 ';
            $sql =$sql.'             and (pais = 0 or (nacional = 1 and pais = '.$idPais.' )) ';
            $sql =$sql.'     ) ';
            $sql =$sql.' order by LangCat.Termo ';

            $categos = DB::select( DB::raw($sql));

        } else {
            // echo 'GetCategos.COM COORDENADAS</p>';

            $sCat = '';
            if ($cat>'') {
                $sCat =' and categoria = '.$cat;
                echo "<button type='button' name='btTudo' onclick='Cate(0);' class='btn-default' >".Lang::get('pagination.tudo')."</button>";
                // echo "<button type='button' name='btTudo' onclick='Cate(".$nrCep.",0);' class='btn-default' >".Lang::get('pagination.tudo')."</button>";
            }

            // ESTE PRIMEIRO SQL SELECIONA AS CATEGORIAS A SEREM MOSTRADAS

            $sql='select id, sum(dentro) as dentro ';
            $sql =$sql.'from ( ';
            $sql =$sql.'select id, 1 as dentro ';
            $sql =$sql.'from ( ';
            $sql =$sql.'SELECT id, fn_distance ('.$lat.', '.$long.', servicos.lat, servicos.lon) distancia , abrangencia ';
            $sql =$sql.'From servicos ';
            $sql =$sql.'Where inativo = 0 ';
            $sql =$sql.'and (( nacional = 0 and pais = '.$idPais.' ) ';
            $sql =$sql.'or pais = 0) ';
            $sql =$sql.') x ';
            $sql =$sql.'Where distancia < abrangencia ';
            $sql =$sql.'Union ';
            $sql =$sql.'select id, 0 as dentro ';
            $sql =$sql.'From servicos ';
            $sql =$sql.'Where inativo = 0 ';
            $sql =$sql.'and (( nacional = 1 and pais = '.$idPais.' ) ';
            $sql =$sql.'or pais = 0) ';
            $sql =$sql.') xx ';
            $sql =$sql.'group by id ';

            $qry_ser1 = DB::select( DB::raw($sql));

            // echo $sql; die;

            $icat = '';
            $esse = '';

            foreach ($qry_ser1 as $id) {

                $esse = $id->id;
                $icat = $icat.$esse.',';

                $this->TemDentro =$this->TemDentro+ $id->dentro;
            }

            if ($icat>"") {
                $icat = substr($icat, 0, strlen($icat)-1);
            }

            $sql = 'select distinct categorias_servicos.id, LangCat.Termo descricao  ';
            $sql =$sql.'from servicos ';
            $sql =$sql.'inner join categorias_servicos on categorias_servicos.id = servicos.categoria ';
            $sql =$sql.'inner join LangCat on LangCat.idCat = categorias_servicos.id ';
            $sql =$sql.'inner join Lang on Lang.idLang = LangCat.Lang ';
            $sql =$sql.'where servicos.id in ('.$icat.') ';
            $sql =$sql.'            and Lang.Sigla = "'.App::getLocale().'" ';
            $sql =$sql.'order by LangCat.Termo ';

            $categos = DB::select( DB::raw($sql));

        }


        if ($this->TemDentro == 0) {
            if ($ResultadoGoogle=="OK") {
                echo '<div class="alert alert-danger">'.Lang::get('messages.NaoTemNaLoc').'</div>';
            } else {
                if ($cep=='') {
                    echo '<div class="alert alert-danger">'.Lang::get('messages.ResultNac').'</div>';
                } else {
                    echo '<div class="alert alert-danger">'.Lang::get('messages.CepInvalido').'</div>';
                }
            }
        }

        foreach ($categos as $catego) {
            $tpBtn='btn btn-default';
            if ($cat>'') {
                if ($catego->id == $cat) {
                    $tpBtn = 'btn-info';
                }
            }
            /*echo "<button type='button' name='Cat=".$catego->id."' class='btn btn-default' >".$catego->descricao."</button>";*/
            echo "<button type='button' name='Cat=".$catego->id."' class='btn btn-default' onclick='Cate(".$catego->id.");' >".$catego->descricao."</button>";
        }
    }

    public function MarcaClick($id) {
        $fazer = true;
        if(Auth::check()) {
            if (Session::get('iduser')==1) {
                $fazer = false;
            }
        }
        if ($fazer == true) {
            DB::update('update categorias_servicos set clicks = clicks + 1 where id = '.$id);
        }
        Session::put('CAT', $id);
    }

    public function GetDescricao($id) {
        if ($id <> $this->old_id) {

            $sql = 'Select LangCat.Termo, categorias_servicos.Obs, categorias_servicos.UsaTele ';
            $sql = $sql.' From categorias_servicos ';
            $sql = $sql.' inner join Lang on Lang.Sigla = "'.App::getLocale().'" ';
            $sql = $sql.' inner join LangCat on LangCat.idCat = categorias_servicos.id  ';
            $sql = $sql.' and LangCat.Lang = Lang.IdLang  ';
            $sql = $sql.' and LangCat.TpCat = 1  ';
            $sql = $sql.' Where categorias_servicos.id = '.$id;
            $categos = DB::select( DB::raw($sql));

            // echo $sql; die;

            /* $catego = DB::table('categorias_servicos')
                      ->select('descricao','Obs','UsaTele')
                      ->where('id','=',$id)
                      ->first(); */

            foreach ($categos as $catego) {
                $this->old_desc = $catego->Termo;
                $this->Obs = $catego->Obs;
                $this->old_id = $id;
                $this->UsaTele = $catego->UsaTele;
                break;
            }
        }
        return $this->old_desc;
    }

    public function GetObs() {
        return $this->Obs;
    }

    public function GetTemDentro() {
        return $this->TemDentro;
    }

    public function GetClicks() {
        if ($this->old_id>'') {
            $Clicks = DB::table('categorias_servicos')
                ->select('clicks')
                ->where('id', '=', $this->old_id)
                ->first();
            return $Clicks->clicks;
        }
        else {
            return 0;
        }
    }

    public function GetUsaTele() {
        return $this->UsaTele;
    }

}