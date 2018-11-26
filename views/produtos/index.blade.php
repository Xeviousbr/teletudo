@extends('layouts.padrao')
<title>
    <?php
    Session::forget('ENTREGA');
    Session::forget('ULTCEP');
    Session::forget('COMPROU');
    Session::get('VlrEntrega');
    Session::get('tpEnt');

    $funciona = true;
    if (App::getLocale()=='') {
        $ClsLocation = new Location;
        $funciona = $ClsLocation->SetaLocal();
    }
    echo Lang::get('produtos.titulo');
    $CliSemEnder = 0;
    $CepDoCli="";
    $idUser = 0;
    $EnderOK = 1;
    if (Auth::check()) {
        $idUser = Auth::id();
        $pessoas = DB::table('pessoa')
            ->select('Endereco_ID','Cep')
            ->where('id','=',$idUser)
            ->first();
        if ($pessoas->Endereco_ID==null) {
            $CliSemEnder = 1;
        }
        $CepDoCli=$pessoas->Cep;

        $cCli = new Clientes();
        $EnderOK = $cCli->EnderOK($idUser);

    }
    $idRede = 0;
    if (isset($_GET['id'])) {
        $idRede = $_GET['id'];
    }
    ?>
</title>
@section('content')

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/1.4.5/numeral.min.js"></script>
<script>
var txtenviar="";
var VlrPedAnt = 0;
var PedAnt="";
var JaSelecionou=0;

// RETIRAR QUANDO O ENDEREÇO DO CLIENTE ESTIVER COMPLETO
// var CliSemEnder=0;
var NumCompra=0;
var CliSemEnder = <?php echo $CliSemEnder; ?>;

// RETIRAR QUANDO PERMITIR MAIS DE UM FORNECEDOR POR PEDIDO
var DoisForn =0;
var EsseFor=0;

var nav = navigator.appVersion;
var A = nav.indexOf("Android");
var nH = "h3"; // h1
var Logado = <?php echo $idUser; ?>;

var idrede = <?php echo $idRede; ?>;
// A=1; // Altera de PC para Celular e vice-versa
// Mas agora tem outro ponto também, mais abaixo $UA = $_SERVER['HTTP_USER_AGENT'];

// Endereço não confirmado ainda não pode comprar
var EnderOK = <?php echo $EnderOK; ?>;

if (A<1) {
    document.cookie = "BRO=PC";
} else {
    nH = "h4";
    document.cookie = "BRO=AN";
}

//
<?php
/*echo $CliSemEnder;*/
?>;

function AtualizaTotal(Total) {
    var Hab = false;
    var total = document.querySelector("#divTotal");
    var sValor = numeral(Total).format('0.00[0000]');

    total.textContent = "Valor Total R$ " + sValor;
    if (Total>0) {
        Hab = true;
    }
    HabOuNao(Hab);
}

function ATM(id, click) {
    var Total = 0;
    var QtdUn = 0;
    var Valor = 0;
    var VltItem = 0;
    var sValor = '';
    var ii = 0;
    var ObjQt="";
    var ObjVl="";
    var Objid="";
    var ObjTpe="";
    var sValor2="";
    var idProd="";
    var ObjFor="";
    var Tpe=0;
    var Qtd = document.getElementById("txQtdItens").value;

    // RETIRAR QUANDO PERMITIR MAIS DE UM FORNECEDOR POR PEDIDO
    var ForAtu=0;
    var lcDoisForn=0;

    txtenviar="";
    Qtd++;
    EsseFor=0;
    for (i = 1; i < Qtd; i++) {
        ObjQt = "txQt" + i;
        ObjVl = "txVlr" + i;
        Objid = "txID"+i;
        ObjTpe = "txTpe"+i;
        ObjFor = "txFor"+i;
        sValor = document.getElementById(ObjVl).innerText;
        var res = sValor.split(",");

        // sValor2 = res[0].substring(2,res[0].length+"."+res[1]);
        sValor2 = res[0].substring(2,res[0].length)+"."+res[1];

        Valor = Number.parseFloat(sValor2);
        QtdUn = document.getElementById(ObjQt).value;
        idProd = document.getElementById(Objid).value;
        Tpe = document.getElementById(ObjTpe).value;
        if (QtdUn>0) {
            ii++;
            VltItem = QtdUn * Valor;
            Total = Total + VltItem;
            if (click==0) {
                if (nH == "h3") { // if (nH == "h1") {
                    AjustaClick(i, VltItem);
                }
            }
            txtenviar=txtenviar+"&q"+ii+"="+QtdUn;
            txtenviar=txtenviar+"&p"+ii+"="+idProd;
            EsseFor = document.getElementById(ObjFor).value;
            if (ForAtu==0) {
                ForAtu= EsseFor;
            } else {
                if (ForAtu!= EsseFor) {
                    lcDoisForn=1;
                }
            }
        }
    }
    txtenviar=txtenviar+"&Qtd="+ii;
    txtenviar=txtenviar+"&t="+Tpe;
    Total+=VlrPedAnt;
    if (lcDoisForn==1) {
        $('#MesmoFor').css({display:"block"});
        DoisForn=1;
    } else {
        if (DoisForn==1) {
            $('#MesmoFor').css({display:"none"});
            DoisForn=0;
        }
    }
    AtualizaTotal(Total);
}

function AjustaClick(i, VltItem) {
    var ObjCk = "Op" + i;
    var ObjTx = "txQt" + i;
    var Checado = document.getElementById(ObjCk).checked;

    if (Checado) {
        if (VltItem==0) {
            document.getElementById(ObjCk).checked=false;
        }
    } else {
        if (VltItem>0) {
            document.getElementById(ObjCk).checked=true;
        }
    }
}

function ChClick(Nr) {
    console.log("ChClick:Estado atual do btConfirma = "+document.getElementById('btConfirma').disabled);
    var ObjCk = "Op" + Nr;
    var ObjTx = "txQt" + Nr;
    var Checado = document.getElementById(ObjCk).checked;
    var Hab = false;

    if (Checado) {
        console.log("ChClick:Checado");
        document.getElementById(ObjTx).value = "1";
        Hab = true;
    } else {
        console.log("ChClick:Não Checado");
        document.getElementById(ObjTx).value = "0";
    }
    if (Hab) {
        console.log("ChClick:Vai entrar VerificaHab");
        VerificaHab();
    }
    console.log("ChClick:HabOuNao("+Hab+")");
    HabOuNao(Hab);
    console.log("ChClick:ATM(-1, 1)");
    ATM(-1, 1);
}

function VerificaHab() {
    var Hab=false;
    var ObjCk = "";
    var Checado = false;
    var Qtd=document.getElementById("txQtdItens").value;
    Qtd++;
    for (i=1;i<Qtd;i++) {
        ObjCk = "Op" + i;
        Checado = document.getElementById(ObjCk).checked;
        if (Checado) {
            Hab=true;
            console.log("VerificaHab:Hab=true");
        }
    }
    console.log("VerificaHab:HabOuNao("+Hab+")");
    HabOuNao(Hab);
}

function HabOuNao(Hab) {

    /*if (CliSemEnder==1) {
     Hab=false;
     NumCompra++;
     if (NumCompra==1) {
     $('#NumCompra').css({display:"block"});
     }
     }*/

    if (DoisForn==1) {
        // console.log("HabOuNao:DoisForn=1");
        Hab=false;
    }

    // Endereço não confirmado ainda não pode comprar
    if (EnderOK==0) {
        // console.log("HabOuNao:DoisForn=1");
        Hab=false;
    }

    console.log("HabOuNao:Estado atual do btConfirma = "+document.getElementById('btConfirma').disabled);

    if (Hab) {
        console.log("HabOuNao:TRUE");
        if (document.getElementById('btConfirma').disabled) {
            document.getElementById('btConfirma').disabled=false;
            console.log("HabOuNao:disabled=false");
        }
    } else {
        console.log("HabOuNao:FALSE");
        if (!document.getElementById('btConfirma').disabled) {
            document.getElementById('btConfirma').disabled=true;
            console.log("HabOuNao:disabled=true");
        }
    }
    console.log("HabOuNao:document.getElementById(btConfirma).disabled = "+document.getElementById('btConfirma').disabled);
}

function Enviar() {
    document.getElementById('btConfirma').disabled=true;
    txtenviar+='&f='+EsseFor;
    txtenviar+=PedAnt;
    txtenviar+="&r="+idrede;

    if (idrede>0) {
        document.location.assign('http://www.tele-tudo.com/criapedido?'+txtenviar);
    } else {
        if (Logado>0) {
            if (CliSemEnder>0) {
                document.location.assign('http://www.tele-tudo.com/entrega/ender');
            } else {
                document.location.assign('http://www.tele-tudo.com/entrega/create?'+txtenviar);
            }
        } else {
			alert(291);
            document.location.assign('http://www.tele-tudo.com/criapedido?'+txtenviar);
        }
    }
}

function Logar() {
    document.location.assign("http://www.tele-tudo.com/login");
}

function Cadastrar() {
    document.location.assign("http://www.tele-tudo.com/pessoa/create");
}

function Seleciona(nr) {
    var ObjTx = "txQt" + nr;
    var qtd = document.getElementById(ObjTx).value
    if (qtd=="0") {
        var est = "ridge 5px yellow";
        Pressionado(nr, est)
        document.getElementById(ObjTx).value = "1";
        Hab = true;

    } else {
        Pressionado(nr, null);
        document.getElementById(ObjTx).value = "0";
        Hab = false;
    }
    HabOuNao(Hab);
    ATM(-1, 1);
    if (JaSelecionou==0) {
        JaSelecionou=1;
        $('#infoSelec').css({display:"none"});
    }
}

function Pressionado(nr, estilo) {
    var oTd = "tdD"+nr;
    var oTv = "tdV"+nr;
    var oTi = "tdI"+nr;

    document.getElementById(oTd).style.borderBottom = estilo;
    document.getElementById(oTd).style.borderLeft = estilo;
    document.getElementById(oTd).style.borderTop = estilo;

    document.getElementById(oTv).style.borderBottom = estilo;
    document.getElementById(oTv).style.borderTop = estilo;

    document.getElementById(oTi).style.borderBottom = estilo;
    document.getElementById(oTi).style.borderTop = estilo;
    document.getElementById(oTi).style.borderRight = estilo;

}

function acionapagina(pag) {
    document.location.assign(pag);
}

</script>
<?php
// $mostrar=false;
$adm=false;
$cep=$CepDoCli;
$pesq='';
$lat = '';
$long = '';
$debug = 0;
$req = '';
$qtItens=0;
Session::put('url', $_SERVER ['REQUEST_URI']);
$Teste = 0;
$logado=0;

if (Auth::check()) {

    $Nome = Session::get('Nome');
    if ($Nome=="") {
        // if (Auth::check()) {
        Session::forget('Nome');
        Session::forget('iduser');
        Session::forget('Debug');
        $cookie = Cookie::forget('Nome');
        $cookie = Cookie::forget('iduser');
        Auth::logout();
        // }
    } else {
        $logado=1;
        if (Session::get('iduser')==21) {
            $Teste = 1;
        }

        if (Session::get('iduser')==1) {
            // MUDAR PARA PEGAR O ADM PELO PERFIL
            // $mostrar=true;
            $adm=true;
        }
    }
}

if ($Teste==1) {
    Session::put('Teste', 1);
} else {
    Session::forget('Teste');
}
?>
<h3 class="text-info">
<?php

$vApp = "0";
if (isset($_GET['CEP'])) {
    $cep=$_GET['CEP'];
}
if ($cep=='') {
    $cep = Session::get('CEP');
    if (Session::has('LAT')) {
        $lat = Session::get('LAT');
        $long = Session::get('LONG');
    }
}

if ($adm==true) {
    $mensagem = Lang::get('messages.caso');
}
else {
    $mensagem = Lang::get('messages.informe');
}
if ($lat=='') {
    $req = 'required';
}

if (isset($_GET['PESQ'])) {
    $pesq = $_GET['PESQ'];
}
$Tpe=0;
if (isset($_GET['Tpe'])) {
    $Tpe = $_GET['Tpe'];
}
echo '</h3>';
?>

<form name="formulario" action="http://www.tele-tudo.com/produtos" method="get">

	<tr>
		<td>
		<p align="center">&nbsp;</p>
		<p align="center">&nbsp;</p>
		<p align="center">
		<img border="0" src="{{ URL::to('download/mensagemindex.png') }}" width="1000" height="100"></p>
		<p align="center">&nbsp;</p>
		<p align="center">&nbsp;</p>
		<p align="center">
		<img border="0" src="{{ URL::to('download/tituloindex.png') }}" width="800" height="100"></p>
		<p align="center">&nbsp;</p>
		<p align="center">&nbsp;</p>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<table border="0" width="400" cellspacing="0" cellpadding="0">
					<tr>
						<td>
						<img border="0" src="{{ URL::to('download/indexcep.png') }}" width="400" height="60"></td>
					</tr>
					<tr>
						<td background="{{ URL::to('download/fundo%20menu.png') }}">
						<p align="center">
						<input type="text" name="CEP" autofocus="" enabled="false" required="" size="40"></td>
					</tr>
					<tr>
						<td>
						<img border="0" src="{{ URL::to('download/indexcep2.png') }}" width="400" height="10"></td>
					</tr>
				</table>
				</td>
				<td>
				<table border="0" width="400" cellspacing="0" cellpadding="0">
					<tr>
						<td>
							<img border="0" src="{{ URL::to('download/indexdesc.png') }}" width="400" height="60">
						</td>
					</tr>
					<tr>
						<td background="{{ URL::to('download/fundo%20menu.png') }}">
						<p align="center">&nbsp;
						<input type="search" results="10" value="" enabled="false" required="" placeholder="Informe a mercadoria que deseja comprar via tele-entrega: " name="PESQ" size="40"></td>
					</tr>
					<tr>
						<td>
						<img border="0" src="{{ URL::to('download/indexdesc2.png') }}" width="400" height="10"></td>
					</tr>
				</table>
				</td>
				<td>
				<table border="0" width="400" cellspacing="0" cellpadding="0">
					<tr>
						<td>
						<img border="0" src="{{ URL::to('download/indexpesq.png') }}" width="400" height="55"></td>
					</tr>
					<tr>
						<td background="{{ URL::to('download/fundo%20menu.png') }}">
							<p align="center">&nbsp;
							<img border="0" src="{{ URL::to('download/btpesq.png') }}" width="160" onclick="aciona()" style="cursor:hand" height="28">
						</td>
					</tr>
					<tr>
						<td>
						<img border="0" src="{{ URL::to('download/indexpesq2.png') }}" width="400" height="5"></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		<p align="center">&nbsp;</p>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<br>
			</tr>
		</table>
		</td>
	</tr>

	@if (Session::has('message'))
		<div class="alert alert-info"><h3>{{ Session::get('message') }}</h3></div>
    @endif
	
    <?php
    $idPed=0;
    $idForn=0;
    if (isset($_REQUEST['ped'])) {
        $idPed = $_REQUEST['ped'];
        $idForn = Session::get('Fornec');
        echo "<input name='ped' type='text' hidden='hidden' value='".$idPed."' /></p>";
        echo "<input name='f' type='text' hidden='hidden' value='".$idForn."' /></p>";
    }
    echo '</form>';

    $cProd = new Produtos;

    $procurar = 0;
    $idPesq = 0;
    if ($pesq>'') {
        if (strlen($pesq)>1) {
            if ($lat=='') {
                if ($cep>'')  {
                    $cCep = new Cep;
                    $status= $cCep->GetCoordenadas($cep, 'prod');
                    if ($status=="OK") {
                        $lat = $cCep->getLat();
                        $long = $cCep->getLong();
                    } else {
                        echo "<div class='alert alert-danger'>".$status."</div>";
                        if ($status==Lang::get('cep.demais')) {
                            echo "<div class='alert alert-info'>Tente novamente amanh&atilde; ou utilize um CEP</div>";
                        }
                    }
                }
            }
        }
        $idPesq = $cProd->Procura($pesq, $cep, $lat, $long, $Teste, $idForn);
        $cid = $cProd->getCid();
        if ($cid>'') {
            $lstLojas = $cProd->getLojas();
            if ($lstLojas>'') {
                $procurar = 1;
            }
        }
    }

    if ($procurar==1) {
        $UA = $_SERVER['HTTP_USER_AGENT'];
        if (strrpos($UA, "Windows")) {
            $BRO = "BRO=PC";
        } else {
            $BRO = "BRO=AN";
        }
        /*BRO = "";
        $BRO = $_COOKIE['BRO'];*/
        $cProd->GetResultados($idPesq, $logado, $BRO);
        $Tpe = $cProd->GetTpe();
        $qtItens=$cProd->Qtd();
    }

    if ($idPed>0) {

        $cEnt = new Entrega();
        $Mais = $cEnt->getValorTotal($idPed);
        // $Mais = $cProd->getValorTotal($idPed);

        $vMais = number_format($Mais, 2, ',', '.');
        $idFornProd = Session::get('Fornec');
        ?>
        <script>
            VlrPedAnt = <?php echo $Mais; ?>;
            idPedAnt = <?php echo $idPed; ?>;
            var sForn = <?php echo $idFornProd; ?>;
            PedAnt = "&ped="+idPedAnt;
            Forn = "&f="+sForn;
            AtualizaTotal(VlrPedAnt);
        </script>
        <?php
        echo "<div class='alert alert-success'>Valor do pedido até agora: R$ ".$vMais."</div>";
        echo "<p><div class='alert alert-info'>Itens adicionais somente do mesmo fornecedor</div><p>";
    }
    $urlredir='';
    if ($qtItens==0) {
        if ($pesq>"") {
            if (strlen($pesq)==1) {
                echo "<div class='alert alert-danger'>Pesquisa muito pequena</div>";
            } else {
                if ($cProd->getLojasNaCidade()) {
                    echo "<div class='alert alert-danger'><font size='5'>Não há lojas abertas vendendo essa mercadoria, na sua região</font></div>";
                } else {
                    echo "<div class='alert alert-danger'><font size='5'>Não existe fornecedores cadastrados na sua cidade</font></div>";
                    echo "<div class='alert alert-info'><font size='5'>Caso queira ser um fornecedor faça um cadastro e nos informe via email xeviousbr@gmail.com</font></div>";
                }
            }
        }
        $cServ = new Servicos();
        $cat='';
        if ($idPesq>0) {
            $cat = $cServ->ProcuraServico($pesq, $lat, $long, $idPesq);
        }
        if ($cat>0) {
            $urlredir="http://www.tele-tudo.com/servicos?CEP=".$cep."&Cat=".$cat;
        }
    }
    if (Auth::check()) {
        ?>
        <div id="MesmoFor" class="alert alert-danger" style="display: none">Voce só pode comprar mercadorias do mesmo fornecedor</div>
        <div id="NumCompra" class="alert alert-danger" style="display: none">Seu endereço precisa ser conferido manualmente pela nossa equipe.
            Amanhã poderá comprar ou entraremos em contato.</div>
        <div class="alert alert-info">{{ 'Usuario Logado: '.$Nome }}</div>
        <?php
        if ($EnderOK==0) {
            echo "<div class='alert alert-danger'>Seu endereço ainda não foi confirmado, impossíve realizar a compra<Br>Atualize para verificar mudança</div>";
        }
        $qry = DB::table('config')
            ->select('Modo')
            ->where('ID', '=', 1)
            ->get();

        switch ($qry[0]->Modo) {
            case 1:
                echo "<p><div class='alert alert-danger'><font size='5'>Antenção: O sistema esta em modo de TESTE (integração simulada)</font></div><p>";
                break;
            case 2:
                echo "<p><div class='alert alert-danger'><font size='5'>Antenção: O sistema esta em modo de TESTE</font></div><p>";
                break;
            case 3:
                // Só pra lembrar que em produção devemos nos lembrar da segurança
                break;
        }
    } else {
        /* $cSes = new Sessao();
        $url = $cSes->urlFace();
        echo "<a href=".$url." class='btn btn-facebook '>Entrar pelo Facebook</a>"; */
    }
    if ($idPesq>0) {
        Session::put('idPesq',$idPesq);
    }
    ?>    
    <table class="table table-striped table-bordered">
        </div>
        <p><input id="txQtdItens" type="text" hidden="hidden" value="{{ $qtItens }}" /></p>
        <script type="text/javascript">
            var QtdItens=document.getElementById("txQtdItens").value;
            if (QtdItens==1) {
                ATM();
            }
        </script>
    </table>
<?php
if ($urlredir>'') {
    echo "<script type='text/javascript'>setTimeout(acionapagina('".$urlredir."'), 3000);</script>";
    }
?>
@stop