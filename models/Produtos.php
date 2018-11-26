<?php

class Produtos extends Eloquent
{
    protected $table = 'produtos';

    // DIMINUIR O TAMANHO DA CLASSE

    /* Fazer uma só para a pesquisa
     *
     * produtos.index
        Procura
        getCid
        GetResultados
        GetTpe
        getLojasNaCidade
    Pessoa.controller
        VeSeTemCidDoCliente
    operacoes.index
        Procura
        getLojas
        BuscaProd
    cadastro
        Deleta
        Atualiza
    pessoa.index
        VeSeTemCidDoCliente
         *
         * */

    private $Cid = '';
    private $lstLojas = '';
    private $texto = '';
    private $Total = 0;
    private $qtd = 0;
    private $Tpe=0;
    private $results=0;
    private $LojasNaCidade=0;
    private $debug=0;
    private $PesCoringa=0;
    private $TiposEmpr = [];
    private $TiposTps = [];

    // PRODUTO/INDEX
    public function Procura($texto, $cep, $lat, $long, $Teste, $idForn) {
        $this->texto = $texto;

        $cep_tmp = str_replace('.','',$cep);
        $cep_tmp = str_replace('-','',$cep_tmp);
        $cep_tmp = str_replace(';','',$cep_tmp);

        if ($Teste==1) {
            $ret=1;
        } else {
            $sql="SELECT Count(*) as Quant ";
            $sql.="FROM procuras ";
            $sql.="WHERE SUBTIME( Now( ) , '00:00:40' ) < data ";
            $sql.=" and texto = '".$texto;
            $sql.="' and cep = ".$cep_tmp;
            $Cons = DB::select( DB::raw($sql));
            if ($Cons[0]->Quant==0) {
                DB::insert('insert into procuras (texto, cep, lat, lon, data) values (?, ?, ?, ?, ?)', [$texto, $cep_tmp, $lat, $long, new DateTime]);
            }
            $ret = DB::table('procuras')->max('id');
        }

        $bairro = 0;
        if (is_numeric($cep)==0) {

            // ENTRA AQUI QUANDO É INFORMADA UMA CIDADE

            if ($lat == '') {
                if (Session::has('LAT')) {
                    $lat = Session::get('LAT');
                    $long = Session::get('LONG');
                } else {
                    $ClsCep = new Cep;
                    $ResultadoGoogle= $ClsCep->GetCoordenadas($cep, 'prod');
                    if ($ResultadoGoogle=="OK") {
                        $lat = $ClsCep->getLat();
                        $long = $ClsCep->getLong();
                        $bairro = ' '.$ClsCep->getLocal();
                    } else {
                        // echo "<div class='alert alert-danger'>".$ResultadoGoogle."</div>";
                        // if ($ResultadoGoogle==Lang::get('cep.demais')) {
                        // 	// Internacionalizar essa mensagem!
                        // 	echo "<div class='alert alert-info'>Voce deve informar o CEP para realizar a pesquisa</div>";
                        // }
                        return 0;
                    }
                }
            }

            $sql = 'select id ';
            $sql = $sql.'from ( ';
            $sql = $sql.'Select cep.id, ';
            $sql = $sql.'fn_distance('.$lat.', '.$long.', cep.lat, cep.lon) distancia ';
            $sql = $sql.'from cep ';
            $sql = $sql.') X ';
            $sql = $sql.'order by distancia ';
            $sql = $sql.'limit 1 ';

            $qry_cep = DB::select( DB::raw($sql));

            $IdCep = '';
            foreach ($qry_cep as $id) {
                $IdCep = $id->id;
            }

            $ConsCep = DB::table('cep')
                ->select('idBairro','idCidade')
                ->where('id','=',$IdCep)
                ->first();
            $bairro = $ConsCep->idBairro;
        } else {

            // CONSULTA O CEP NA BASE DE DADOS
            $ConsCep = DB::table('cep')
                ->select('idBairro','idCidade')
                ->where('NrCep','=',$cep_tmp)
                ->first();
            if ($ConsCep==null) {
                $ClsCep = new Cep;
                $ResultadoGoogle= $ClsCep->GetCoordenadas($cep, 'prod');
                if ($ResultadoGoogle=="OK") {

                    if ($lat=='') {
                        echo 'Google Ok mas lat vazio'; die;
                    }

                    $ClsCep->GetCidadePelaLoc($lat, $long);
                    $bairro=$ClsCep->getBairro();
                }
            } else {
                $bairro=$ConsCep->idBairro;
                if ($bairro=='') {
                    // VOU VER SE DA PRA DEIXAR SEM O BAIRRO

                    // $bairro = $this->CadastraCepBairro($cep_tmp);
                    // echo "Bairro nao cadastrado para este cep";
                    // exit(0);
                }
            }
        }

        // echo '$bairro'

        if ($lat>'') {
            // DEDUZ A CIDADE
            $idCid = 0;
            if ($bairro > 0) {
                $ConsBai = DB::table('bairro')
                    ->select('idcidade')
                    ->where('id','=',$bairro)
                    ->first();
                $idCid = $ConsBai->idcidade;
            } else {
                if ($ConsCep!=null ) {
                    if ($ConsCep->idCidade>0) {
                        $idCid = $ConsCep->idCidade;
                    }
                }
            }
            if ($idCid>0) {
                $ConsCid = DB::table('cidade')
                    ->select('NomeCidade')
                    ->where('ID','=',$idCid)
                    ->first();
                $this->Cid = $ConsCid->NomeCidade;
            }
            if ($idForn>0) {

                $this->setLojas($idForn);
                // $this->lstLojas = $idForn;

            } else {
                if ($Teste==1) {
                    $sql = "SELECT empresa.idEmpresa, empresa.tpEntrega, empresa.DistMax ";
                    $sql.="FROM empresa ";
                    $ConsLojas = DB::select( DB::raw($sql));
                } else {
                    $ConsLojas = $this->SelecionaLojas($lat, $long, $idCid, $Teste, 0);
                }
                if ($ConsLojas!=null) {
                    $this->ProcuraParte2($ConsLojas, $Teste, $idCid, $bairro, 0);
                }
            }
        }
        $this->loga('ret ='.$ret);
        return $ret;
    }

    public function VeSeTemCidDoCliente($cep, $idCid) {
        $Cons = DB::table('cep')
            ->select('cep.lat','cep.lon','cep.idCidade','cep.idBairro')
            ->where('NrCep','=',$cep)
            ->first();
        $bairro = $Cons->idBairro;
        $lat = $Cons->lat;
        $long = $Cons->lon;
        if ($idCid==0) {
            $idCid = $Cons->idCidade;
        }
        $ConsLojas = $this->SelecionaLojas($lat, $long, $idCid, 0);
        $this->ProcuraParte2($ConsLojas, 0, $idCid, $bairro, 1);
        return $this->LojasNaCidade;
    }

    private function ProcuraParte2 ($ConsLojas, $Teste, $idCid, $bairro, $tipo) {
        $entregadora = DB::table('entregadoras')
            ->select('DistMax')
            ->where('ID','=',1)
            ->first();
        $DistMax = $entregadora->DistMax;

        date_default_timezone_set('America/Sao_Paulo');
        $lstLojas = '';

        $ConsConfig = DB::table('config')
            ->select('Debug')
            ->where('ID','=',1)
            ->first();
        $this->debug=$ConsConfig->Debug;

        foreach ($ConsLojas as $Lojas) {
            $ok=0;
            $this->LojasNaCidade++;
            $TeUtilizado = -1;
            if ($Teste==1) {
                $ok=1;
            } else {

                // $date1 = date('Y-m-d H:i:s');
                $date1 = date('Y-m-d H:i:s',strtotime("-1 hours", strtotime(date('Y-m-d H:i:s'))));

                $Tempo=$this->time_diff($date1,$Lojas->dtON);
                $this->loga('<Br>idCid = '.$idCid);
                $this->loga('idEmpresa = '.$Lojas->idEmpresa);
                $this->loga('date1 = '.$date1);
                $this->loga('Lojas->dtON = '.$Lojas->dtON);
                $this->loga('tempo='.$Tempo);
                $ok1=false;
                if ($tipo==1) {
                    // PROCURA CIDADE DO CLIENTE QUE SE CADASTROU
                    $ok1=true;
                } else {
                    // VE SE TEM A LOJA ABERTA PRA COMPRAR

                    // Se der errado, aumentar o tempo
                    if ($Tempo<97) {
                        $ok1=true;
                    }
                }
                if ($ok1==true) {
                    $ok=0;

                    $this->loga( 'tempo = '.$Tempo);
                    $this->loga( 'tpEntrega='.$Lojas->tpEntrega);

                    switch ($Lojas->tpEntrega) {
                        case 0:
                            // PLAYDELIVERY
                            if ($Lojas->distancia<$DistMax) {
                                $ok=1;
                                $TeUtilizado=0;
                            }
                            $this->loga( 'PLAYDELIVERY');
                            $this->loga( 'Lojas->distancia = '.$Lojas->distancia);
                            $this->loga( 'DistMax='.$DistMax);
                            $this->loga( 'ok='.$ok);
                            break;
                        case 1:
                            // ENTREGA PRÓPRIA POR DISTÂNCIA
                            $this->loga( 'ENTREGA PRÓPRIA POR DISTÂNCIA');
                            $this->loga( 'Lojas->distancia = '.$Lojas->distancia);
                            $this->loga( 'DistMax='.$DistMax);
                            if ($Lojas->distancia < $Lojas->DistMax) {
                                // echo 'ok = 1<Br>';
                                $ok=1;
                                $TeUtilizado=1;
                            }
                            break;
                        case 2:
                            // POR BAIRRO
                            $qtd = DB::table('TpEntregaEmpresa')
                                ->select(DB::raw('count(*) as Quant'))
                                ->where('idEmpresa', '=', $Lojas->idEmpresa)
                                ->where('idBairro', '=', $bairro)
                                ->first();
                            if ($qtd->Quant>0) {
                                $ok=1;
                                $TeUtilizado=2;
                            }
                            $this->loga('POR BAIRRO');
                            break;

                    }

                    // PREVISÃO PARA ENTREGA INTEGRADA ONDE A PRÓPRIA NÃO ENTREGA
                    $this->loga('ok = '.$ok);
                    if ($ok==0) {
                        $this->loga('Lojas->idEntrega = '.$Lojas->idEntrega);
                        if ($Lojas->idEntrega==2) {
                            $this->loga($Lojas->distancia.'<'.$DistMax);
                            if ($Lojas->distancia<$DistMax) {
                                $ok=1;
                                $this->loga('ok=1');
                                $TeUtilizado=0;
                            }
                        }
                    }
                } else {
                    break;
                }
            }
            if ($ok==1) {
                $lstLojas = $lstLojas.$Lojas->idEmpresa.',';
                $this->loga('lstLojas = '.$lstLojas);
                $this->loga('Lojas->idEmpresa = '.$Lojas->idEmpresa);
                $this->loga('TeUtilizado = '.$TeUtilizado);
                array_push($this->TiposEmpr,$Lojas->idEmpresa);
                array_push($this->TiposTps,$TeUtilizado);
            }
        }
        if ($lstLojas>'') {
            // echo "lstLojas > '' <Br>";
            $lstLojas = substr($lstLojas, 0, strlen($lstLojas)-1);

            $this->setLojas($lstLojas);
            // $this->lstLojas = $lstLojas;

            $this->results=1;
            $this->loga('this->results = '.$this->results);
        } else {
            // echo "lstLojas = '' <Br>";
            if ($idCid>0) {

                // NÃO EXISTEM LOJAS DISPONIVEIS PARA ESTA CIDADE
                $qtd = DB::table('empresa')
                    ->select(DB::raw('count(*) as Quant'))
                    ->where('idcidade', '=', $idCid)
                    ->first();

                if ($qtd->Quant>0) {

                    // $ConsLojas = $this->SelecionaLojas($lat, $long, $idCid, 1);


                    // echo 'TEM LOJAS, MAS NENHUMA ABERTA'; die;
                    // TEM LOJAS, MAS NENHUMA ABERTA

                    $this->results=-1;
                    $this->LojasNaCidade=1;
                    // $ret=-1;

                } else {
                    echo 'this->results=0'; die;

                    $this->results=0;
                    // $ret =0;
                }
            } else {
                // TENTAR TRAZER OS FORNECEDORES PELA PROXIMIDADE
                $this->results=0;
            }
        }
    }

    private function time_diff($dt1,$dt2){
        $y1 = substr($dt1,0,4);
        $m1 = substr($dt1,5,2);
        $d1 = substr($dt1,8,2);
        $h1 = substr($dt1,11,2);
        $i1 = substr($dt1,14,2);
        $s1 = substr($dt1,17,2);

        $y2 = substr($dt2,0,4);
        $m2 = substr($dt2,5,2);
        $d2 = substr($dt2,8,2);
        $h2 = substr($dt2,11,2);
        $i2 = substr($dt2,14,2);
        $s2 = substr($dt2,17,2);

        $r1=date('U',mktime($h1,$i1,$s1,$m1,$d1,$y1));
        $r2=date('U',mktime($h2,$i2,$s2,$m2,$d2,$y2));
        return ($r1-$r2);
    }

    public function getLojas() {
        return $this->lstLojas;
    }

    private function setLojas($Lojas) {
        $this->lstLojas = $Lojas;
    }

    public function GetResultados($idPesq, $logado, $App) {
        $this->Total =0;
        $results = $this->PesquisaEmSi(1);
        if ($results==null) {
            if ($logado==0) {
                $results = $this->PesquisaEmSi(0);
            }
        }
        if ($results!=null) {
            DB::update("update procuras set encontrado = ".$this->results." where id = ".$idPesq );

            $this->quant=sizeof($results);

            // VER SE TEM UM RESULTADO OU MAIS (essa operação creio que possa ser melhorada)
            $QtdIn='0';
            $che='';
            if ($this->quant==1) {
                $QtdIn='1';
                $che='checked="checked"';
            }
            ?>
            <table>
                <?php
                $nrLin=0;
                $Total1=0;
                $tpEntrega=0;
                if ($App=="AN") {
                    if ($this->quant>0) {
                        echo "<p><div id='infoSelec' class='alert alert-info'>Pressione na imagem para selecionar</div><p>";
                    }
                }
                if ($this->getPescGenerica()) {
                    echo "<div class='alert alert-info'>Não localizamos o que voce procura '".strtoupper($this->texto)."'&emsp;Verifique os artigos parecidos</div>";
                }
                foreach ($results as $result) {
                    $tpEntrega=$result->tpEntrega;
                    echo '<tr>';
                    $nrLin++;
                    if ($nrLin==1) {
                        $Total1=$result->Valor;
                    }
                    if ($result->Imagem == null) {
                        $Imagem = "http://www.tele-tudo.com/mapa/Caixa.png";
                    } else {
                        $Imagem = $result->Imagem;
                    }
                    $ImgNom = $result->ImgNorm;
                    if ($ImgNom==0) {
                        $ImgFmt="style='height: 105px; width: 139px'";
                    } else {
                        $ImgFmt="";
                    }
                    $ItAr = array_search($result->Empresax_ID, $this->TiposEmpr);
                    $Tpe = $this->TiposTps[$ItAr];
                    $this->loga('Tpe = '.$Tpe);
                    if ($App=="AN") {
                        $Linha = $this->MontaLinhaA($nrLin, $result, $Imagem, $QtdIn, $ImgFmt, $Tpe);
                    } else {
                        $Linha = $this->MontaLinhaPc($nrLin, $result, $Imagem, $QtdIn, $ImgFmt, $Tpe, $che);
                    }
                    echo '</tr>';
                }
                $this->qtd=$nrLin;
                if ($this->quant==1)
                    $this->Total=$Total1;
                ?>
            </table>
            </Br>
            <div id="divTotal"><input id="txTotal" readonly="readonly" type="text" value=" Valor Total R$ <?php echo number_format($this->Total, 2); ?> " /></div>
            <!--<input id="txTpe" name="txTpe" type="hidden" value="<?php /*echo $tpEntrega; */?>">-->
            <?php
            if ($this->quant==1) {
                $desab="";
            } else {
                $desab="disabled=false";
            }
            echo "<td width='144'><input type='button' id='btConfirma' value='Confirma compra' onclick='Enviar()' ".$desab." class='btn btn-lg btn-success' /></td><p>";
            ?>
            <input id="txQtdItens" type="text" value=<?php echo '"'.$this->quant.'"'; ?> hidden >
            <?php
            $this->Tpe=$Linha;
        }
    }

    public function GetTpe() {
        return $this->Tpe;
    }

    public function Qtd() {
        return $this->qtd;
    }

    public function getLojasNaCidade() {
        // echo 'this->LojasNaCidade'.$this->LojasNaCidade; die;
        return $this->LojasNaCidade;
    }

    public function getCid() {
        return $this->Cid;
    }

    private function ConsParaIn($Cons) {
        $INs = '';
        foreach ($Cons as $reg) {
            $INs .= $reg->id.',';
            // echo $reg->id."<Br>";
        }
        if ($INs>'') {
            $INs = substr($INs, 0, strlen($INs)-1);
        }
        // echo $INs;
        return $INs;
    }

    // FUNÇÕES PRIVADAS
    private function SelecionaLojas($lat, $long, $idCid) {
        // SELECIONAR PRIMEIRO AS LOJAS
        $sql = "SELECT idEmpresa id ";
        $sql.="FROM empresa ";
        $sql.="Where idcidade = ".$idCid;
        $ConsLojas = DB::select( DB::raw($sql));
        $Ins = $this->ConsParaIn($ConsLojas);
        if ($Ins=='') {
            return null;
        } else {
            // SELECIONAR QUAIS DESTAS ESTÃO ABERTAS
            $sql = "SELECT empresa.idEmpresa, empresa.tpEntrega, empresa.DistMax, empresa.idEntrega";
            $sql.=", fn_distance(".$lat.", ".$long.", lat, lon) distancia, dtON ";
            $sql.="FROM empresa ";
            $sql.="inner join endereco on endereco.ID = empresa.idEndereco ";
            $sql.="inner join cep on cep.id = endereco.idCep ";
            $sql.="Where idEmpresa IN (".$Ins.") ";
            $sql.="Order by dtON desc ";
            $ConsLojas = DB::select( DB::raw($sql));
            return $ConsLojas;
        }
    }

    private function MontaLike($campo) {
        $textos = explode(' ', $this->texto);
        $qtd = count($textos);
        if ($qtd==1) {
            return $campo." like '%".$this->texto."%' ";
        } else {
            $TextoRes = "";
            for($i=0;$i<$qtd;$i++)
            {
                $TextoRes.=" ".$campo." LIKE '%".$textos[$i]."%'";
                if ($i<($qtd-1)) {
                    $TextoRes.=" and ";
                }
            }
            return $TextoRes;
        }
    }

    private function PesquisaEmSi($competo)
    {
        if ($this->lstLojas > "") {
            //
        } else {
            $competo = 0;
        }
        $this->loga('PesquisaEmSi:this->lstLojas =' . $this->lstLojas);
        if ($competo == 1) {
            $whereO = " inner join empresa on empresa.idEmpresa = produtos.Empresax_ID";

            $whereO .= " where produtos.Disponivel=1 and Empresax_ID in (" . $this->lstLojas . ") and ";
            // $whereO .= " where Empresax_ID in (" . $this->lstLojas . ") and ";

            $tpen = ", empresa.tpEntrega ";
        } else {
            $whereO = "where ";
            $tpen = ", 1 tpEntrega ";
        }

        // PRIMEIRA BUSCA, PROCURA PELO TERMO EXATO
        $sql = "select produtos.ID, produtos.Nome, produtos.Valor, produtos.Descricao, produtos.Imagem, produtos.ImgNorm, produtos.Empresax_ID" . $tpen;
        $sql .= "from produtos ";
        $termo = " Nome = '" . $this->texto . "' ";
        $where = $whereO . $termo;
        $sqlf = $sql . $where;
        $results = DB::select(DB::raw($sqlf));
        $this->loga('PesquisaEmSi 1 ' . $termo);

        if ($results == null) {
            // SEGUNDA BUSCA, PROCURA POR ALGO QUE INICIE PELO TERMO EXATO
            $termo = " Nome like '" . $this->texto . "%'";
            $where = $whereO . $termo;
            $sqlf = $sql . $where;
            // echo $sqlf; die;
            $results = DB::select(DB::raw($sqlf));
            $this->loga('PesquisaEmSi 2 ' . $termo);
            if ($results == null) {
                // TERCEIRA BUSCA, PROCURA POR QUALQUER ITEM COM OS TERMOS MENCIONADOS
                $termo = $this->MontaLike("Nome");
                $where = $whereO . $termo;
                $sqlf = $sql . $where;
                // echo $sqlf; die;
                $results = DB::select(DB::raw($sqlf));
                $this->loga('PesquisaEmSi 3 ' . $termo);

                // PROCURA SOMENTE PELA DESCRIÇÃO
                if ($results == null) {
                    $termo = " Descricao =  '" . $this->texto . "' ";
                    $where = $whereO . $termo;
                    $sqlf = $sql . $where;
                    $results = DB::select(DB::raw($sqlf));
                    $this->loga('PesquisaEmSi 4 ' . $termo);

                    // SEGUNDA BUSCA, PROCURA POR ALGO QUE INICIE PELO TERMO EXATO
                    if ($results == null) {
                        $termo = " Descricao like '" . $this->texto . "%'";
                        $where = $whereO . $termo;
                        $sqlf = $sql . $where;
                        $results = DB::select(DB::raw($sqlf));
                        $this->loga('PesquisaEmSi 5 ' . $termo);

                        // TERCEIRA BUSCA, PROCURA POR QUALQUER ITEM COM OS TERMOS MENCIONADOS
                        if ($results == null) {
                            $termo = $this->MontaLike("Descricao");
                            $where = $whereO . $termo;
                            $sqlf = $sql . $where;
                            $results = DB::select(DB::raw($sqlf));
                            $this->loga('PesquisaEmSi 6 ' . $termo);
                            if ($results == null) {
                                // BUSCA POR PALAVRAS CORINGAS
                                $this->loga('PesquisaEmSi 7 ' . $this->texto);
                                $results = $this->BuscaCoringas();
                                if ($results == null) {

                                    // BUSCA POR EMPRESAS
                                    /* $this->loga('PesquisaEmSi 8 ' . $this->texto);
                                    $results = $this->BuscaEmpresas(); */

                                    $this->setLojas('');
                                } else {
                                    $termo = " produtos.CategoriasProdutos_ID = " . $results;
                                    $where = $whereO . $termo;
                                    $sqlf = $sql . $where;
                                    $results = DB::select(DB::raw($sqlf));
                                    $this->loga('PesquisaEmSi 8 ' . $termo);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $results;
    }

    private function BuscaCoringas() {
        // $texto = $this->texto;
        $texto = $this->semacento($this->texto);
        $letrafinal = substr($texto, -1);
        if ($letrafinal=='s') {
            $texto = substr($texto, 0, strlen($texto)-1);
        }
        $this->loga( 'BuscaCoringas '.$texto);
        $sql = "select categoria from palavras where palavra = '".$texto."'";
        $this->loga( 'sql = '.$sql);
        $consP = DB::select( $sql);
        if ($consP==null) {
            $this->loga( 'BuscaCoringas:return null');
            return null;
        } else {
            $this->loga( 'BuscaCoringas:consP[0]->categoria = '.$consP[0]->categoria);
            $this->PesCoringa = $consP[0]->categoria;
            return $consP[0]->categoria;
        }
    }

    private function getPescGenerica() {
        return $this->PesCoringa;
    }

    private function MontaLinhaA($nrLin, $result, $Imagem, $QtdIn, $ImgFmt, $Tpe) {
        echo "<td width='494' id='tdD".$nrLin."' style='font-size: small;' >".$result->Nome."</Br><input type='number' style='text-align: center;' class='form-control' onkeyup='ATM(".$nrLin.",0)' onchange='ATM(".$nrLin.",0)' id='txQt".$nrLin."' Value = '".$QtdIn."' ></td>";
        echo "<input id='txID".$nrLin."' name='txID".$nrLin."' type='hidden' value='".$result->ID."'>";
        echo "<td width='100' id='tdV".$nrLin."'><label id='txVlr".$nrLin."' onclick='Seleciona(".$nrLin.")'>R$ ".number_format($result->Valor, 2, ',', '.')."</label>";
        echo '<input id="txFor'.$nrLin.'" name="txFor'.$nrLin.'" type="hidden" value="'.$result->Empresax_ID.'">';
        echo '<input id="txTpe'.$nrLin.'" name="txTpe'.$nrLin.'" type="hidden" value="'.$Tpe.'">';
        echo '</td>';
        echo "<td width='100' id='tdI".$nrLin."' onclick='Seleciona(".$nrLin.")' ><img src='".$Imagem."' ".$ImgFmt." /></td>";
        return $result->tpEntrega;
    }

    private function MontaLinhaPc($nrLin, $result, $Imagem, $QtdIn, $ImgFmt, $Tpe, $che) {
        $descr = '<td width="494">'.$result->Nome;
        if ($result->Descricao>'') {
            $descr.='<br>'.$result->Descricao;
        }
        $descr.='</td>';
        echo $descr;

        echo '<input id="txID'.$nrLin.'" name="txID'.$nrLin.'" type="hidden" value="'.$result->ID.'">';
        echo '<td width="100"><label id="txVlr'.$nrLin.'">R$ '.number_format($result->Valor, 2, ',', '.').'</label>';
        echo '<input id="txFor'.$nrLin.'" name="txFor'.$nrLin.'" type="hidden" value="'.$result->Empresax_ID.'">';
        echo '<input id="txTpe'.$nrLin.'" name="txTpe'.$nrLin.'" type="hidden" value="'.$Tpe.'">';
        echo '</td>';
        echo "<td width='100'><img src='".$Imagem."' ".$ImgFmt." /></td>";
        echo '<td width="70"><input type="number" style="text-align: center;" class="form-control" onkeyup="ATM('.$nrLin.',0)" id="txQt'.$nrLin.'" Value = "'.$QtdIn.'" ></td>';
        echo '<td><label class="control-label"><input type="checkbox" id="Op'.$nrLin.'" onkeyup="ATM('.$QtdIn.',0)" onclick="ChClick('.$nrLin.')" value="" '.$che.'> escolher</label></td>';
        return $result->tpEntrega;
    }

    public function Deleta($id) {
        DB::update("delete from produtos where id = ".$id);
    }

    private function asu($m) {
        if ($m=='') {
            $m = 'null';
        } else {
            $m = "'".$m."'";
        }
        return $m;
    }

    public function Atualiza($id, $p, $n, $m, $d, $c) {
        $m = $this->asu($m);
        $d = $this->asu($d);
        DB::update("update produtos set nome = '".$n."', Valor = ".$p.", Imagem = ".$m.", Descricao = ".$d.", CategoriasProdutos_ID = ".$c." updated_at=now() Where ID = ".$id);
    }

    private function semacento($umarray){
        $palavra = strtolower($umarray);
        $comacento = array('á','â','à','ã','é','ê','è','ó','ô','ò','õ','í','î','ì','ú','û','ù','ç');
        $acentohtml   = array('a','a','a','a','e','e','e','o','o','o','o','i','i','i','u','u','u','c');
        $ret  = str_replace($comacento, $acentohtml, $palavra);
        return $ret;
    }

    private function loga($texto) {
        if ($this->debug==1) {
            echo $texto.'<Br>';
        }
    }

    public function Desativa($id) {
        DB::update("update produtos set Disponivel  = 0 where ".$id);
    }

    public function Ativa($id) {
        DB::update("update produtos set Disponivel  = 1 where ".$id);
    }

}