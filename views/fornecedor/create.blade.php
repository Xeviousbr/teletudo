<?php $idUser = 0; ?>
@extends('layouts.padrao')
<link href="http://voky.com.ua/showcase/sky-forms/examples/css/sky-forms.css" rel="stylesheet" type="text/css" />
<title>Cadastro da Fornecedor</title>
<script>
</script>
@section('content')
<?php
$idUser = 0;
if (Session::has('iduser')) {
    $idUser = Session::get('iduser');
}
if ($idUser==0) {
    echo 'Àrea apenas para usuário registrado'; die;
}
// $idUser = Auth::id();
$qry = DB::table('pessoa')
    ->select('Nome','fone','email','user')
    ->where('id', '=', $idUser)
    ->first();
$NomeUser = $qry->Nome;
$FoneUser = $qry->fone;
$MailUser = $qry->email;
$cForn = new Fornecedor();
$Categos = $cForn->getCategosEmpresas();
?>
<h2>Cadastro de Fornecedor</h2>
<form class="sky-form boxed" style="width: 344px; border-width: medium; border-color: #FF0000;" method="POST" action="http://www.tele-tudo.com/fornecedor" accept-charset="UTF-8">
    <input name="TipoProd" id="TipoProd" type="hidden" value="">
    <?php
    if ($qry->user==null) {
        ?>
        <Br>
        <div>
            &nbsp;<label for="usuario">Usuario</label>
            <input required="required" name="usuario" type="text" id="empresa" value="">
        </div>
        <Br>
        <div>
            &nbsp;<label for="senha">Senha</label>
            <input required="required" name="senha" type="text" id="empresa" value="">
        </div>
    <?php
    }
    ?>
    <Br>
    <div>
        &nbsp;<label for="empresa">Nome da Empresa</label>
        <input required="required" name="empresa" type="text" id="empresa" value="{{$NomeUser}}">
    </div>
    <Br>
    <div>
        &nbsp;<label for="Telefone">Telefone da Empresa</label>
        <input required="required" name="Telefone" type="text" id="Telefone" value="{{$FoneUser}}">
    </div>
    <Br>
    <div>
        &nbsp;<label for="email">Email da Empresa</label>
        <input required="required" name="email" type="text" id="email" value="{{$MailUser}}">
    </div>
    <Br>

    <div>
        &nbsp;<label for="email">Data de acerto (1 - 28)</label>
        <input required="required" name="DiaAcerto" type="number" step="1" id="DiaAcerto" style="width: 57px" min="1" max="28" value="10">
        <Br>&nbsp;Data para fazer o repasse ao tele-tudo
    </div>
    <br>

    <div>
        &nbsp;<label for="fone">Tipo de Produtos Vendidos</label>
        <select id="cbTipoProd" name="cbTipoProd" onchange="VeSeHab()" >
            {{$Categos}}
        </select>
    </div>
    <br>
    <div>
        &nbsp;{{ Form::label('fone', 'Tipo de Entrega') }}
        <select id="Tpe" name="Tpe" onchange="Mudou()">
            <option value="0">Escolha</option>
            <option value="1">Sómente entrega própria</option>
            <option value="2">Sómente entrega do site</option>
            <option value="3">As duas formas de entregas</option>
        </select>
    </div>
    <div class="bs-callout text-center" style="color: #0000FF" id="dvMens">

    </div>
    <div class="bs-callout text-center">
        &nbsp;{{ Form::label('Obs', 'Observações: Utilize como achar necessário') }}
        &nbsp;<textarea name="Obs" rows="2" style="width: 305px"></textarea>
    </div>
    <Br>

    <button type="submit" name="btSalvar" id="btSalvar" disabled class="btn btn btn-primary btn-block">Salvar cadastro</button>
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script>

    function VeSeHab() {
        var Tpe = document.getElementById("Tpe").value;
        var DesaHabilita = true;
        if (Tpe>0) {
            DesaHabilita = false;
            console.log('DesaHabilita = false');
        }
        var Tf = document.getElementById("cbTipoProd").value;
        if (Tf>0) {
            DesaHabilita = false;
            console.log('DesaHabilita = false');
        }
        document.getElementById('btSalvar').disabled=DesaHabilita;
        document.getElementById("TipoProd").value = Tpe;
    }

    function Mudou() {
        var Tpe = document.getElementById("Tpe").value;
        if (Tpe == 0) {
            var Mens = 'Conforme a escolha pode ser necessário mais configurações';
        } else {
            if (Tpe == 1) {
                var Mens = 'É necessário mais configurações.<Br>Entraremos em contato';
            } else {
                if (Tpe == 2) {
                    var Mens = 'Não é necessário mais configurações';
                } else {
                    var Mens = 'É necessário mais configurações.<Br>Entraremos em contato';
                }
            }
        }
        document.getElementById("dvMens").innerHTML = "<label id='lbMens'>"+Mens+"</label>";
        VeSeHab();
    }
</script>
@stop