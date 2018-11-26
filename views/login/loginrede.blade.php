@extends('layouts.padrao')
<title>Solicitando informações da Rede</title>
@section('content')
<?php

$idRede = 123;
$nome  = 'Testelino';
$email  = 'testelino@gmail.com';
$telefone = '51995139696';
$url = "www";
$idPedido = $_REQUEST['idPedido'];

?>

<div class="row">

<main class="container container-fluid col-xs-12 " >

<span class="glow" style="display:inline; padding:0 6px; color:#ffff; text-shadow:0 0 1em red,0 0 1em red, 0 0 1.2em red; font-size:xx-large;">Solicitando informações da Rede Social</span>

<!--<form action="http://www.tele-tudo.com/ender?id='.$idRede.'&nome='.$nome.'" method="post" name="formRede">-->

<?php echo'<form action="http://www.tele-tudo.com/ender" method="post" name="formRede">

<input type="hidden" name="idRede" value="'.$idRede.'"/>

<input type="hidden" name="nome" value="'.$nome.'"/>

<input type="hidden" name="email" value="'.$email.'"/>

<input type="hidden" name="fone" value="'.$telefone.'"/>

<input type="hidden" name="url" value="'.$url.'"/>

<input type="hidden" name="idPedido" value="'.$idPedido.'"/>

</form>';?>

<script language="javascript" type="text/javascript">

    document.formRede.submit();

</script>

<?php

// echo $pesq['email'];

?>

</span>
<?php
exit(0); die;
?>

// Este é o nr do id da tua rede a que estou pedido informações
$idRede = $_REQUEST['id'];

// Este é o id do pedido, ao me enviar as informações deve me enviar ele também
$idPedido = $_REQUEST['idPedido'];

/*O envio das informações é por Post
Campos
idrede
nome
email
fone
idPedido

O endereço a ser enviado é
http://www.tele-tudo.com/ender

Porque o próximo passo é o usuário complementar o cadastro com o endereço

*/
?>
<span class="glow" style="display: inline; padding: 0 6px; color: #ffffff; text-shadow: 0 0 1em red, 0 0 1em red, 0 0 1.2em red; font-size: xx-large;">Solicitando informações da Rede</span>
<form action="http://www.tele-tudo.com/ender " method=post name="fakerede">
    <input type="hidden" name="idrede" value="123">
    <input type="hidden" name="nome" value="Testelino">
    <input type="hidden" name="email" value="testelino@gmail.com">
    <input type="hidden" name="fone" value="51995139696">
    <input type="hidden" name="idPedido" value="{{$idPedido}}">
</form>
<script language="javascript" type="text/javascript">
    document.fakerede.submit();
</script>
@stop