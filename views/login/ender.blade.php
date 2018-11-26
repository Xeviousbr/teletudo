<?php
Session::put('SemDown',1);
?>
@extends('layouts.padrao')
<title>Cadastro de Clientes e Colaboradores do Teletudo</title>

<link href="http://voky.com.ua/showcase/sky-forms/examples/css/sky-forms.css" rel="stylesheet" type="text/css" />

<style type="text/css">
    .normal {
        border-width: thin;
        width:330px; height:25px;
        border-color: #000000;
    }
</style>

<script>

    var CheKAceito=0;

    function CEPKeyUp() {
        var Cep = document.getElementById("txCep").value;
        Cep = Cep.replace('.','');
        Cep = Cep.replace('-','');
        Cep = Cep.trim();
        var Tam = Cep.length;
        if (Tam==8) {
            AtuCep(Cep)
        }
    }

    function AtuCep(Cep) {
        $(function(){
                $.ajax({
                    url: "https://viacep.com.br/ws/"+Cep+"/json",
                    dataType: "html",
                    success: function(result){
                        var R = eval('(' + result + ')');
                        if (R.uf === undefined) {
                            document.getElementById("txES").value = '';
                            document.getElementById("estado").value = '';
                            document.getElementById("txCid").value = '';
                            document.getElementById("Bairro").value = '';
                            document.getElementById("Endereco").value = '';
                        } else {
                            document.getElementById("txES").value = R.uf;
                            document.getElementById("estado").value = Estado(R.uf);
                            document.getElementById("txCid").value = R.localidade;
                            document.getElementById("Bairro").value = R.bairro;
                            if (R.logradouro>'') {
                                document.getElementById("Endereco").value = R.logradouro+', ';
                            }
                            SetaBorta('txCep',0);
                            SetaBorta('Endereco',1);
                            SetaBorta('fone',1);
                            if (R.bairro=='') {
                                SetaBorta('Bairro',1);
                            }
                        }
                    },
                    error: function(result) {
                        alert("CEP Inválido!");
                    }
                });
            }
        )
    };

    function SetaBorta(Objeto, Tipo) {
        if (Tipo==1) {
            document.getElementById(Objeto).style.borderColor="#FF0000";
            document.getElementById(Objeto).style.borderWidth="medium";
        } else {
            document.getElementById(Objeto).style.borderColor="#000000";
            document.getElementById(Objeto).style.borderWidth="thin";
        }
    }

    function Estado(UF) {
        var UFs = ['SC','AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SP','SE','TO'];
        var ES = ['Santa Catarina','Acre','Alagoas','Amapá','Amazonas','Bahia','Ceará','Distrito Federal','Espírito Santo','Goiás','Maranhão','Mato Grosso','Mato Grosso do Sul','Minas Gerais','Pará','Paraíba','Paraná','Pernambuco','Piauí','Rio de Janeiro','Rio Grande do Norte','Rio Grande do Sul','Rondônia','Roraima','São Paulo','Sergipe','Tocantins']
        for (var i = 0; i < UFs.length; i ++) {
            if (UF==UFs[i]) {
                return ES[i];
            }
        }
    }

    function voltar() {
        history.back();
    }

    function Salvar_Click() {
        var Bairro = document.getElementById("Bairro").value;
        if (Bairro=='') {
            alert("Informe o bairro");
            document.getElementById("Bairro").select();
        } else {
            var Ender = document.getElementById("Endereco").value;
            var Ender2 = Ender.trim();
            var Len = Ender2.length;
            var UltLetra = Ender2.charAt(Len - 1);
            if (UltLetra == ',') {
                alert("Complete o endereço");
                document.getElementById("Endereco").select();
            } else {
                document.endereco.submit();
            }
        }
    }

    function LePolitica () {
        window.open('http://www.tele-tudo.com/privacidade', '_blank');
    }

    function Aceito () {
        if (CheKAceito) {
            CheKAceito=0;
            console.log('emcima');
            document.getElementById("btSalvar").disabled=true;
        } else {
            CheKAceito=1;
            console.log('embaixo');
            document.getElementById("btSalvar").disabled=false;
        }
    }

</script>

@section('content')

<?php

Session::put('SemChat',1);

$cep='';
$estado='';
$estadoGR='';
$cidade='';
$bairro='';
$Endereco='';
$fone='';
$mail='';
$StyCep = "border-width: medium; border-color: #FF0000;";

if (Session::has('erro')) {
    $erro = Session::get('erro');
    echo "<div class='alert alert-danger'><b>".$erro."</b></div>";
    $cep=$_GET['cep'];
    $estadoGR=$_GET['estado'];
    $estado=$_GET['sigla_estado'];
    $cidade=$_GET['cidade'];
    $bairro=$_GET['Bairro'];
    $Endereco=$_GET['Endereco'];
    $fone=$_GET['fone'];
}

?>
<label style="font-size: medium">Precisamos do seu endereço logo em seguida poderá realizar sua compra</label>
<?php

if (isset($_REQUEST['idRede'])) {
    loga('ENTROU');
    $nome = $_REQUEST['nome'];
    $email = $_REQUEST['email'];
    $fone = $_REQUEST['fone'];
    $idrede = $_REQUEST['idRede'];
    $idPedido = $_REQUEST['idPedido'];
    $first_name = '';
    $idFace = '';
} else {
    loga('não ENTROU');
    // echo 'não ENTROU<Br>';
    $first_name = Session::get('first_name');
    $nome = Session::get('faceName');
    $idFace = Session::get('idFace');
    $email = '';
    $fone = '';
    $idrede = '';
    $idPedido = '';
}
loga('idrede:'.$idrede);
loga('fone:'.$fone);
loga('idPedido:'.$idPedido);

?>
<form name="endereco" method="POST" action="http://www.tele-tudo.com/pessoa" class="sky-form boxed" style="width: 344px">
    <input name="idFace" type="hidden" value="{{ $idFace }}">
    <input name="idRede" type="hidden" value="{{ $idrede }}">
    <input name="tpEnder" type="hidden" value="E">
    <input name="first_name" type="hidden" value="{{ $first_name }}">
    <input name="email" type="hidden" value="{{ $email }}">
    <input name="fone" type="hidden" value="{{ $fone }}">
    <input name="faceName" type="hidden" value="{{ $nome }}">
    <input name="idPedido" type="hidden" value="{{ $idPedido }}">
    <p style="font-size: large">&nbsp;CEP<br>
        &nbsp;<input type="number" required onkeyup="CEPKeyUp()" name="txCep" step="10" id="txCep" style="{{$StyCep}}; width:330px; height:25px;" value="{{$cep}}" ><br>
    <p style="font-size: large">&nbsp;Estado<br>
        &nbsp;<input type="text" required name="estado" id="estado" class="normal" value="{{$estadoGR}}" >
        <input type="text"  name="txES" id="txES" value="{{$estado}}" style="visibility: hidden; display: none" >
        <br>
    <p  style="font-size: large">&nbsp;Cidade<br>
        &nbsp;<input type="text" required name="txCid" id="txCid" class="normal" value="{{$cidade}}" ><br>
    <p style="font-size: large">&nbsp;Bairro<br>
        &nbsp;<input type="text" required name="Bairro" id="Bairro" class="normal" value="{{$bairro}}" ><br>
    <p style="font-size: large">&nbsp;Endereço<br>
        &nbsp;<input type="text" required name="Endereco" id="Endereco" class="normal" value="{{$Endereco}}" ><br>
    <p style="font-size: large">&nbsp;Telefone para contato<br>
        &nbsp;<input type="text" name="fone" id="fone" class="normal" value="{{$fone}}" ><br>
    <p style="font-size: large">&nbsp;email<br>
        &nbsp;<input type="email" required name="email" id="fone" class="normal" value="{{$mail}}" ><br>
    <table style="width: 100%">
        <tr>
            <td>
                <label style="font-size: small">
                    <input type="checkbox" onclick="Aceito();">&nbsp;Aceito os termos de privacidade
                </label>

            </td>
            <td style="text-align: right;">
                <input name="btPol" type="button" onclick="LePolitica()" value="Política de privacidade">
            </td>
        </tr>
        <tr>
            <td>
                <button type="button" onclick="voltar();" class="btn btn-warning btn-lg">Retornar</button>
            </td>
            <td style="text-align: right">
                <button type="submit" name="btSalvar" id="btSalvar" disabled class="btn btn-success btn-lg">Salvar</button>
            </td>
        </tr>
    </table>

</form>
<?php
if (Session::has('erro')) {
    $Nrerro = Session::get('Nrerro');
    if ($Nrerro==1) {
        ?>
        <script>
            SetaBorta('Endereco', 1);
            SetaBorta('cep', 0);
        </script>
        <?php
    } else {
        echo 'NÂO entrou<Br>';
    }
    Session::forget('erro');
    Session::forget('Nrerro');
}
?>
@stop
<?php
function loga($texto) {
    DB::update("insert into LogDebug (Log) values ('ender:".$texto."')");
}
?>