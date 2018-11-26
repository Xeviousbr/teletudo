<?php $idUser=0; ?>
@extends('layouts.padrao')

@section('content')

<h1>Escolha a forma de pagamento<h1>


<script Language="JavaScript">
    function Outras() {
        alert('outras');
    }
</script>

<?php
$Teste=0;
if (Session::has('Teste')) {
    $Teste=1;
}

$cSessao = new Sessao;

// $OnLine = $cSessao->VeSf();
$OutrDisab='disabled';
if ($Teste==1) {
    // $OnLine=1;
    $OutrDisab='';
}

/*if ($OnLine==0) {
    echo "<a class='btn btn-small btn-success btn-lg btn-block' disabled >Transferência Bancária</a>";
    echo "<p><div class='alert alert-danger'><font size='5'>Sistema Financeiro não disponível no momento</font><font size='2'>  Não será possível colocar a loja On-Line</font></div><p>";
} else {*/
    echo "<a class='btn btn-small btn-success btn-lg btn-block' href=".URL::to('vlrtransf/create/').">Transferência Bancária</a>";
// }

$Ped = $_GET['ped'];

$Valor = Session::get('VLRTOTAL');
$Valor = $Valor * 100;
?>

<form action="https://www.moip.com.br/PagamentoMoIP.do" method="POST">

    <!--Sua identificação no MoIP. Pode ser seu e-mail principal, celular verificado ou login.	Alfanumérico	45-->
    <input type="hidden" name="id_carteira" value="xeviousbr@gmail.com">

    <!--O valor da transação, sem vírgulas e identificador da moeda	Numérico (inteiro)	9-->
    <input type="hidden" name="valor"  value="{{$Valor}}">

    <input type="hidden" name="id_transacao"  value="{{$Ped}}">

    <!--Razão do pagamento	Razão do pagamento a ser mostrado na página do MoIP, durante o processo de confirmação (nome do produto/serviço)	Alfanumérico	64-->
    <input type="hidden" name="nome"  value="Pagamento do Tele-Tudo.com, por compra realizada">

    <input type="submit" class="btn btn-info btn-block btn-lg btn-primary {{$OutrDisab}}" value="Outras opções">

    <!--<a href="http://desenvolvedor.moip.com.br/sandbox/" target="_blank"><img src="http://desenvolvedor.moip.com.br/sandbox/imgs/banner_5_1.jpg" border="0"></a>-->

</form>

@stop