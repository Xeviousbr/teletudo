<!DOCTYPE html>
<html>		
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php    
    $ClsLocation = new Location;
    $ObsIP = $ClsLocation->GetOpc();	    
    if ($ObsIP==null) {
    	echo '<title>Informação sobre a Renovação</title>';
    } else {
	echo '<title>Entre para renovar</title>';    
    }
?>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
</head>

<body>
	
<div class="container">
<nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
        <li><a href="{{ URL::to('produtos') }}">{{ Lang::get('menus.Produtos') }}</a></li>
        <li><a href="{{ URL::to('/servicos') }}">{{ Lang::get('menus.Servicos') }}</a></li>

        @if(Auth::check())
            <li><a href="{{ URL::to('sair') }}">{{ Lang::get('menus.Deslogar') }}</a>
        @else
            <li><a href="{{ URL::to('login') }}">{{ Lang::get('menus.Login') }}</a>
        @endif
        <li><a href="{{ URL::to('contatos') }}">{{ Lang::get('menus.como') }}</a></li>

    </ul>
</nav>

<img src="{{ Lang::get('menus.img') }}"/>
<h1>Renovação do anúncio para <span style="color: #0000ff">
<?php

$pagar->GetReg($pagar->ID);
$nome = $pagar->getNome();
echo $nome;
?>
</span></h1>
<h2>Estamos solicitando uma contribuição.</h2>
<h3>Não é obrigatório.</h3><br>
    <h2>Oferecemos vantagens caso queira contribuir.<br>
        - Destaque no anúncio.<br>
        - Aumento da abrangência. </h2>Atualmente é de {{ $pagar->getAbrangencia() }}<br>
    - Direito a ser uma franquia <span class="font-size: small">do site assim
que esse recurso estiver disponibilizado.</span>
<p>&nbsp;</p>
<p><span class="style4">Valor </span><span class="style3"><strong>R$
<?php
echo $pagar->getValor();
Session::put('Valor', $pagar->getValor());
?></strong></span></p>
<p><span class="style5">Data limite do pagamento: </span><span class="style2">
<?php
echo date('d/m/Y', strtotime($pagar->getLimPag()));
?>
</span></p>
    <?php
    $idTrans=$pagar->getIDTRANS();
    if ($idTrans>0) {
        ?>
        De acordo com nossos registros foi declarado um pagamento<br>
        <strong>mas ainda não esta confirmado</strong> <br>
        caso queira alterar as informações do pagamento clique <input name="btAlterarInf" type="submit" value="AQUI" disabled="disabled">
        <?php

        // Status

        // Clicado
        // Não informado
        // Informado                    $idTrans>0  vlrtransf.Status = 0
        // Em Processo de confirmação   $idTrans>0  vlrtransf.Status = 1
        // Rejeitada a informação       $idTrans>0  vlrtransf.Status = 3
        // Confirmado o pagamento       $idTrans>0  vlrtransf.Status = 2

        // Em processo de entrega       A informação deve estar contida numa tabela que gerencie a transferência
        // Entrega finalizada           " "

        // vlrtransf
        // Status
        //  0 - Default, na criação do registro
        //  1 - Visualizado que foi informado
        //  2 - Confirmado o pagamento
        //  3 - Não houve pagamento (depois de certo período de averiguação)

        // servico.COBRAR
        // 0 = Não cobrar
        // 1 = Cobrar
        // 2 = Não possível fazer cobrança no momento
        // 3 = Pago para este ano

    } else {
        ?>
        <form name="formCat"
              action="http://www.tele-tudo.com/vlrtransf/create/"
              method="get">
            O valor deve ser pago na forma de transferência bancária<br>
            para o banco <input name="txBanco" type="text" value="Itaú" size="5" style="height: 22px" onclick="this.select()" readonly="readonly" ></strong></strong>, Agência
                <input name="txAgencia" type="text" value="0296" size="5" style="height: 22px" onclick="this.select()" readonly="readonly" >,
            Conta
                <input name="txConta" type="text" value="90450-1" size="8" style="height: 22px" onclick="this.select()" readonly="readonly">
                , CPF<input name="txCPF" type="text" value="502.336.290-68" size="14" style="height: 22px; width: 110px;" onclick="this.select()" readonly="readonly">.<br>
                <input name="txPagto" type="text" value="{{$pagar->ID}}" style="visibility: hidden;" >
            <br><br>
            Clique <input name="btInformar" type="submit" value="AQUI"> para informar o pagamento
        </form>
        <?php
        }
    ?>
    <br>
    Aqui abaixo uma visualização do seu anúncio na forma de visualização por perfil
    <iframe src="http://www.tele-tudo.com/servicos/{{$pagar->getIDSERV()}}?iFra=1" style="width: 811px; height: 538px" scrolling="yes" name="iFrame"></iframe>
    <strong><br>
        Caso tenha dúvidas ou sugestões utilize o email
        <input name="txEmail" type="text" value="xeviousbr@gmail.com" size="20" style="height: 26px; width: 150px;" onclick="this.select()" readonly="readonly" >
    </strong>

    <!--Start of Tawk.to Script-->
@if (Auth::check()==0)
<script type="text/javascript">
    var $_Tawk_API = {}, $_Tawk_LoadStart = new Date();
    (function () {
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/55a73bfb84d307454c01fcd3/default';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
@endif
<!--End of Tawk.to Script-->     

</div>
</body>
</html>
<?php
if ($ObsIP==null) {
    $pagar->RegistraVisita();
}
?>