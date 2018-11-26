<?php
use Illuminate\Auth\UserInterface;

// class Fornecedor extends Eloquent
class Fornecedor extends Eloquent  implements UserInterface
{
    protected $table = 'empresa';
    private $idEmpresa = 0;
    private $idPessoa = 0;
    private $Nome = '';
    private $user = '';
    private $tpEntrega = 0;
    private $vRepasse = 0;
    private $DiaAcerto=0;
    private $QtdRegs=0;
    private $QtdPPag=20;
    private $idEntrega=0;
    private $QtdConfgEntregas=0;

    // UserInterface

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    // UserInterface


    public function SetIdPessoa($id) {
        $qry = DB::table('pessoa')
            ->join('empresa', 'empresa.idPessoa', '=', 'pessoa.id')
            ->select('empresa.idEmpresa', 'empresa.Empresa', 'empresa.tpEntrega','empresa.DiaAcerto',
                'pessoa.user')
            ->where('pessoa.id', '=', $id)
            ->get();
        if ($qry!=null) {
            $this->idEmpresa = $qry[0]->idEmpresa;
            $this->Nome = $qry[0]->Empresa;
            $this->user = $qry[0]->user;
            $this->idPessoa=$id;
            $this->settpEntrega($qry[0]->tpEntrega);
            $this->DiaAcerto=$qry[0]->DiaAcerto;
            return true;
        } else {
            return false;
        }
    }

    public function getNome() {
        return $this->Nome;
    }

    public function getUser() {
        // return 'this->user';
        return $this->user;
    }

    public function getNotificacoes() {
        if ($this->gettpEntrega()==0) {
            // PELA PLAY-DELIVERY
            $qry = DB::table('notificacao')
                ->join('pedido', 'pedido.idPed', '=', 'notificacao.idPedido')
                ->join('pessoa', 'pessoa.id', '=', 'pedido.User')
                ->join('vlrtransf', 'vlrtransf.id', '=', 'notificacao.idTransf')
                ->select('notificacao.Valor', 'notificacao.Hora','notificacao.idAviso','notificacao.vizualizado','notificacao.Confirmado',
                    'pessoa.Nome','pessoa.fone',
                    'vlrtransf.id as idTrans','vlrtransf.BCO', 'vlrtransf.AGE','vlrtransf.CTA',
                    'pedido.idPed','pedido.status as stPedido')
                ->where('notificacao.idFornec', '=', $this->idEmpresa)
                ->where('notificacao.Ativo', '=', 1)
                ->get();
        } else {
            $qry = DB::table('notificacao')
                ->join('pedido', 'pedido.idPed', '=', 'notificacao.idPedido')
                ->join('pessoa', 'pessoa.id', '=', 'pedido.User')
                ->select('notificacao.Valor', 'notificacao.Hora','notificacao.idAviso','notificacao.vizualizado',
                    'pessoa.Nome','pessoa.fone',
                    'pedido.idPed','pedido.status as stPedido')
                ->where('notificacao.idFornec', '=', $this->idEmpresa)
                ->where('notificacao.Confirmado', '=', null)

                // Tava desabilitado e habilitei para funcionar para o fornecedor, Rancho
                ->where('notificacao.Ativo', '=', 1)

                ->get();
        }
        return $qry;
    }

    /*    public function Visualizou($id) {
             DB::update("update notificacao set vizualizado = now() where idAviso = " .$id);
        }*/

    /*    public function ConfirmouCEntrega($id) {
            DB::update("update notificacao set Confirmado = now() where idAviso = " .$id);
        }*/

    /*    public function Confirmou($id,$idPed, $idTrans) {
            DB::update("update notificacao set Confirmado = now() where idAviso = " .$id);

            DB::insert('insert into pagamento (TP, idTrans, idPed) values (?, ?, ?)',
                [
                    1,
                    $idTrans,
                    $idPed
                ]);
        }	   */

    public function getSaldo() {
        $qry = DB::table('conta')
            ->select('Saldo','Pendente')
            ->where('idPessoa', '=', $this->idPessoa)
            ->get();
        if ($qry==null) {
            DB::insert("insert into conta (idPessoa, Saldo) values (?, ?)",[$this->idPessoa, 0]);
            $Saldo = 0;
            $Pendente=0;
        } else {
            $Saldo = $qry[0]->Saldo;
            $Pendente = $qry[0]->Pendente;
        }
        $this->vRepasse=$Pendente;
        return number_format($Saldo, 2, ',', '.');
    }

    public function getRepasse() {
        return number_format($this->vRepasse, 2, ',', '.');
    }

    public function OnLine() {
        if ($this->EnderOK($this->idEmpresa)==1) {
            DB::update("update empresa set dtON = now(), TpAcesso = 0 where idEmpresa = " .$this->idEmpresa);
            return 1;
        } else {
            return 0;
        }
    }

    private function EnderOK ($idForn) {
        $qry = DB::table('empresa')
            ->select('logradouro.adic')
            ->join('endereco', 'endereco.ID', '=', 'empresa.idEndereco')
            ->join('logradouro', 'logradouro.ID', '=', 'endereco.Logradouro_ID')
            ->where('empresa.idEmpresa', '=', $idForn)
            ->first();
        if ($qry->adic==1) {
            return 0;
        } else {
            return 1;
        }
    }

    public function FornOff($idForn) {
        DB::update("update empresa set dtON = null where idEmpresa = " .$idForn );
    }

    public function EsseFornOnLine($idForn) {
        if ($this->EnderOK($idForn)==1) {
            DB::update("update empresa set dtON = now(), TpAcesso = 1 where idEmpresa = " .$idForn );
            // $idForn=10;
            $Qtd = DB::table('notificacao')
                ->where('idFornec', '=', $idForn)
                ->whereNull('vizualizado')
                ->whereNull('Confirmado')
                ->count();
            return $Qtd;
        } else {
            return 0;
        }
    }

    public function gettpEntrega() {
        return $this->tpEntrega;
    }

    public function settpEntrega($tpEntrega) {
        $this->tpEntrega = $tpEntrega;
    }

    public function getDataRepasse() {
        $dia  = mktime (0, 0, 0, date("m")  , $this->DiaAcerto, date("Y"));
        $hoje = date('d/m/y');
        if ($dia>$hoje) {
            // NA VERDADE DEVE IR PARA O MES QUE VEM E NÃO ADICIONAR UM MES, VAI DAR ERRO NO MES 12
            // NA VERDADE NÃO TEM QUE SÓ PEGAR O PRÓXIMO MES, MAS VERIFICAR SE HOUVE PAGAMENTO
            //      E SE NÃO HOUVE INDICAR QUE HÁ ATRASO
            $dia  = mktime (0, 0, 0, date("m")+1, $this->DiaAcerto, date("Y"));
        }
        $ODia = date('d/m/Y' , $dia);
        return $ODia;
    }

    public function Visualizou($id) {;
        DB::update("update notificacao set Ativo = 0 where idAviso = " .$id);
    }

    public function getTotLista($busca) {
        if ($busca>'') {
            $sql = "select count(*) as Quant ";
            $sql.="from produtos ";
            $sql.="where Empresax_ID = ".$this->idEmpresa;
            $sql.=" and (Nome like '%".$busca."%' ";
            $sql.=" or Descricao like '%".$busca."%' )";
            $qry = DB::select( DB::raw($sql));
            $this->QtdRegs =$qry[0]->Quant;
        } else {
            $this->QtdRegs = DB::table('produtos')
                ->where('Empresax_ID', '=', $this->idEmpresa)
                ->count();
        }
        return $this->QtdRegs;
    }

    public function ProdForn($pag, $busca) {
        $in=($pag-1)*20;
        if ($busca>'') {
            $sql = "select ID, Nome, Descricao, Valor, CategoriasProdutos_ID, Imagem, ImgNorm, Peso, Disponivel ";
            $sql.="from produtos ";
            $sql.="where Empresax_ID = ".$this->idEmpresa;
            // $sql.=" and Disponivel = 1 ";
            $sql.=" and (Nome like '%".$busca."%' ";
            $sql.=" or Descricao like '%".$busca."%' ) ";
            $sql.="limit ".$this->QtdPPag;
            $sql.=" offset ".$in;
            $qry = DB::select(DB::raw($sql));
        } else {
            $qry = DB::table('produtos')
                ->select('ID','Nome', 'Descricao', 'Valor', 'CategoriasProdutos_ID', 'Imagem', 'ImgNorm', 'Peso', 'Disponivel')
                ->where('Empresax_ID', '=', $this->idEmpresa)
                // ->where('Disponivel', '=', 1)
                ->skip($in)->take(20)
                ->get();
        }
        return $qry;
    }

    public function Paginacao($pag, $QtdPaginacoes) {
        $essapag = "http://www.tele-tudo.com/Cadastro";
        $qtsPags = intval($this->QtdRegs/$this->QtdPPag);

        /*echo 'QtdRegs:'.$this->QtdRegs.'<Br>';
        echo 'QtdPPag:'.$this->QtdPPag.'<Br>';
        echo 'qtsPags:'.$qtsPags.'<Br>';*/

        $sim = false;
        if (($this->QtdRegs>$this->QtdPPag) or ($qtsPags>1)) {
            $sim = true;
        }

        if ($sim) {
        // if ($qtsPags>1)

            $Qtd=0;
            $Aux=$QtdPaginacoes/2;
            echo "<ul class='pagination'>";
            if ($pag>$Aux) {
                $Qtd=$this->LinhaPag($essapag, '1','«', $Qtd);
            }
            $min = $pag-($QtdPaginacoes/2);
            if ($min<0) {$min = 1;}

            $max = ($qtsPags+2);

            /*echo 'min:'.$min.'<Br>';
            echo 'max:'.$max.'<Br>';*/

            for ($i=$min; $i<$max; $i++) {
            // for ($i=$min; $i<($qtsPags+1); $i++) {
                //

                // echo 'i:'.$i.'<Br>';

                if ($i>0) {
                    if ($Qtd==($QtdPaginacoes-1)) {
                        $Qtd= $this->LinhaPag($essapag, $qtsPags, '»', $Qtd);
                        break;
                    } else {
                        if ($i!=$pag) {
                            $Qtd = $this->LinhaPag($essapag, $i, $i, $Qtd);
                        } else {
                            $Qtd = $this->LinhaPag(null, $pag, $pag, $Qtd);
                        }
                    }
                }
            }
        }
    }

    private function LinhaPag($essapag, $pag, $tit, $Qtd) {
        if ($essapag!=null) {
            echo "<li><a href='".$essapag."?pag=".$pag."'>".$tit."</a></li>";
        } else {
            echo "<li><a disabled='disabled' >".$tit." </a></li>";
        }
        $Qtd++;
        return $Qtd;
    }

    public function getidEmpresa() {
        return $this->idEmpresa;
    }

    public function getidUltPedDia()
    {
        $sql = "SELECT notificacao.idPedido, notificacao.Hora ";
        $sql .= "FROM notificacao ";
        $sql .= "Inner Join pedido on pedido.idPed = notificacao.idPedido and pedido.status < 5 ";
        $sql .= "Where notificacao.idFornec = " . $this->idEmpresa;
        $sql .= " and SUBTIME( Now( ) , '23:59:59' ) < notificacao.Hora ";
        $sql .= " order by notificacao.Hora desc ";
        $Cons = DB::select(DB::raw($sql));
        if ($Cons == null) {
            return 0;
        } else {
            return $Cons[0]->idPedido;
        }
    }

    public function getUltCompra($idPed) {
        $qry = DB::table('notificacao')
            ->join('pedido', 'pedido.idPed', '=', 'notificacao.idPedido')
            ->join('pessoa', 'pessoa.id', '=', 'pedido.User')
            ->select('notificacao.Valor',
                'pessoa.Nome','pessoa.fone', 'pessoa.Endereco_ID')
            ->where('idPedido', '=', $idPed)
            ->first();
        return $qry;
    }

    public function getCategosEmpresas() {
        $qry = DB::table('categoriasempresas')
            ->select('Descricao')
            ->where('ID', '>', 1)
            ->orderBy('Descricao')
            ->get();
        $ret = "<option value='0'>Escolha</option>";
        $i = 0;
        foreach ($qry as $Cats) {
            $ret.= "<option value='".$i."'>".$Cats->Descricao."</option>";
            $i++;
        }
        return $ret;
    }

    public function getCatProds($idEmpresa) {
        $qryE = DB::table('empresa')
            ->select('categoriasempresas_ID')
            ->where('idEmpresa', '=', $idEmpresa)
            ->first();
        $qry = DB::table('categoriasprodutos')
            ->select('Descricao','TipoCategoria_ID')
            ->get();
        $ret = "";
        $sel = false;
        $i = 1;
        foreach ($qry as $Cats) {
            $sele='';
            if ($sel==false) {
                if ($qryE->categoriasempresas_ID == $Cats->TipoCategoria_ID) {
                    $sele=' selected ';
                    $sel = true;
                }
            }
            $ret.= "<option value='".$i."' ".$sele." >".$Cats->Descricao."</option>";
            $i++;
        }
        return $ret;
    }

    public function ConfgEntregas($idUser) {
        $this->idPessoa=$idUser;
        $qryE = DB::table('empresa')
            ->select('idEmpresa')
            ->where('idPessoa', '=', $idUser)
            ->first();
        if ($qryE==null) {
            return 0;
        } else {
            $this->idEmpresa=$qryE->idEmpresa;
            $qryEn = DB::table('TpEntregaEmpresa')
                ->where('idEmpresa', '=', $this->idEmpresa)
                ->count();
            $this->QtdConfgEntregas = $qryEn;
            return $this->QtdConfgEntregas;
        }
    }

    public function getOpLocRest() {
        $arrai = array('','');
        $qry = DB::table('empresa')
            ->select('tpEntrega','idEntrega')
            ->where('idEmpresa', '=', $this->idEmpresa)
            ->first();
        $this->idEntrega = $qry->idEntrega;
        if ($this->idEntrega==0) {
            $op=1;
        } else {
            $op=0;
        }
        $arrai[$op]=" checked='checked' ";
        return $arrai;
    }

    public function getTpEntregas() {
        return 'ret';

        /*
        tpEntrega
        Tem que ver que tipo de entrega que é
            0 = PlayDelivery
            1 = Tem a própria entrega e configura por distância
            2 = Tem a própria entrega e configura por bairro

        idEntrega
            0 = Sómente entrega própria
            1 = Sómente entrega do site
            2 = As duas formas de entregas

        Se tem a própria entrega (1 e 2)
        Precisa de pelo menos um registro em TpEntregaEmpresa */

        // return "Aqui deveria ter dados 'Distancia','idBairro','Valor'";
    }

    public function getResumoConfEntr() {
        $ret="";
        switch ($this->idEntrega) {
            case 0:
                // Sómente entrega própria
                if ($this->QtdConfgEntregas==0) {
                    $ret = "Inválida é necessário ajustar";
                } else {
                    $ret = "Entrega feita pelo fornecedor";
                }
                break;
            case 1:
                // Sómente entrega do site
                if ($this->QtdConfgEntregas==0) {
                    $ret = "Entrega feita pelo site";
                } else {
                    $ret = "Entrega feita pelo fornecedor e pelo site";
                }
                break;
            case 2:
                // As duas formas de entregas
                if ($this->QtdConfgEntregas==0) {
                    $ret = "Entrega feita pelo site";
                } else {
                    $ret = "Entrega feita pelo fornecedor e pelo site";
                }
                break;
        }
        return $ret;
    }

}