<?php $idUser = 0; ?>
@extends('layouts.padrao')
<title>Tele Tudo - Produtos - Confirmação da Compra - Sem Login</title>
@section('content')
<?php
$tpEnt = $_REQUEST['t'];
$forn = $_REQUEST['f'];
Session::put('FORN', $forn);
$cPed = new Pedido;
$Teste=0;
if (Session::get('iduser')==21) {
    $Teste = 1;
}
$cSessao = new Sessao;
$CriarPedido=false;
if (isset($_REQUEST['ped'])) {
    $idPedido = $_REQUEST['ped'];
} else {
    $cPed->CriaPedido(0, $Teste, $tpEnt);
    $idPedido = $cPed->getIdPedido($Teste);
}
$tpEnt = $cSessao->tpEntrega($idPedido);
Session::put('PEDIDO', $idPedido);
Session::put('TpEntrega', $tpEnt);
$QtdItens = $_REQUEST['Qtd'];
$QtdItens++;
for ($i=1;$i<$QtdItens;$i++) {
    if ($Teste==0) {
        $ClsItens = new PedidoItens;

        $q=$_REQUEST['q'.$i];
        $ClsItens->setQtd($q);

        $p=$_REQUEST['p'.$i];
        $ClsItens->setIdProd($p);

        $ClsItens->Add($idPedido);
    } else {
        $ClsItens = new PedidoItens;
        break;
    }
}

$idFornProd = $ClsItens->getidFornProd();
Session::put('Fornec',$idFornProd);

$url="login";
$idRede=$_REQUEST['r'];
if ($idRede>0) {
    $Cons = DB::table('pessoa')
        ->select('id','Nome')
        ->where('RedeID', '=', $idRede)
        ->first();
    if ($Cons==null) {
        $url="loginrede";

        // obterDados.php
        ?>
        <form action="loginrede" method=post name="teletudopede">
            <input type="hidden" name="id" value="{{$idRede}}">
            <input type="hidden" name="idPedido" value="{{$idPedido}}">
        </form>
        <script language="javascript" type="text/javascript">
            document.teletudopede.submit();
        </script>
        <?php
        exit(0);
    } else {
        $idUser=$Cons->id;
        $Nome=$Cons->Nome;
        Session::put('idRede',$idRede);
        Auth::loginUsingId($idUser);
        $cEntrega = new Entrega();
        DB::update("update pedido set User = '".$idUser."' where idPed = ".$idPedido);
        $idEntrega = $cEntrega->CriaRegistro($idPedido, $idUser, $Teste, $tpEnt);
        $cEntrega->setidEntrega($idEntrega);
        $VlrOrc = $cEntrega->PedeOrcamento($idPedido, $Teste, $tpEnt);

        if ($VlrOrc>0) {

            $cEntrega->setVlrEntrega($VlrOrc);
            Session::put('ENTREGA', $idEntrega);

            if ($tpEnt>0) {
                Session::put('Kms',$cEntrega->getKms());
                Session::put('TmpPrevisto',$cEntrega->getTmpPrevisto());
            }
            ?>
            <div class="alert alert-info">{{ 'Usuario Logado: '.$Nome }}</div>
            <script language="javascript" type="text/javascript">
                var idPedido = <?php echo $idPedido; ?>;
                document.location.assign("http://www.tele-tudo.com/confirma?IDPED="+idPedido);
            </script>
            <?php
        } else {
                echo 'Não foi possível obter a informação referente a Tele-Entrega[2]';
        }
        exit(0);
    }
} else {
    $url="login";
}
?>
<script language="javascript" type="text/javascript">
document.location.assign("http://www.tele-tudo.com/{{$url}}");
</script>
@stop