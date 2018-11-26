<?php
$idUser=0;
?>
@extends('layouts.padrao')
<title>Cadastro de Clientes e Colaboradores do Teletudo</title>
<!--<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />-->

<!--<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />-->

<script>

    var UltCep=0;
    var CheKAceito=0;

    // var ret = eval('(' + result + ')');
    // console.log(ret.idCep);

    // Se não tiver na base, pegar no Google
    // Mostrar

    // Fazer a pesquisa de cidades por estado
    // Só se não tiver vindo pelo cep

    // Fazer a pesquisa de bairros por cidade
    // Só se não tiver vindo pelo cep

    // Fazer sempre a pesquisa de logradouro por bairro
    // Mas manter o logradouro se tiver sido mostrado

    function AtuCep(Cep) {
        console.log("AtuCep("+Cep+")");
        console.log("M = "+Mobile);
        $(function(){
            $.ajax({
                url: "http://www.tele-tudo.com/processo?op=7&cep="+Cep+'&M='+Mobile, // CEP
                dataType: "html",
                success: function(result){

                    // console.log("var R1 = eval(( + result + ))");
                    var R1 = eval('(' + result + ')');

                    // console.log("var JS1 = JSON.stringify(R1)");
                    var JS1 = JSON.stringify(R1);

                    // console.log("var JS = eval(( + JS1 + ))");
                    var JS = eval('(' + JS1 + ')');

                    // console.log("if (JS.OK>0) {");
                    if (JS.OK>0) {

                        document.getElementById('endereco').innerHTML = "";
                        $('#dvLogra').css({visibility:"visible"});
                        $('#dvLogra').css({display:"block"});

                        // console.log("(endereco).append(JS.EstCidBai)");
                        $("#endereco").append(JS.EstCidBai);

                        // console.log("if (JS.qtRuas>0) {");
                        if (JS.qtRuas>0) {

                            $('#cbLogra').html(JS.Logra).show();

                            // console.log("(cbLogra).on(change, NovoLog)");
                            $('#cbLogra').on('change', NovoLog);

                        } else {
                            $('#dvtxlog').css({display:"block"});
                            $('#dvcblog').css({display:'none'});
                            $('#dvcblog').css({visibility:'hidden'});
                        }
                    } else {
                        $("#endereco").append("<h3 style='color: #FF0000'>Impossivel obter dados sobre o CEP.<Br>Entre em contato comigo para efetivar o cadastro<Br>xeviousbr@gmail.com </h3>");
                    }
                }
            });
        });
    }

    function KeyUp() {
        var Cep = document.getElementById("txCep").value;
        Cep = Cep.replace(".","");
        Cep = Cep.replace("-","");
        var Tam = Cep.length;
        // console.log(Tam);
        if (Tam==8) {
            if (Cep!=UltCep) {
                AtuCep(Cep);
                UltCep=Cep;
            }
        }
    }

    function NovoLog() {
        var escolhido = $('#cbLogra').find(":selected").text();
        if (escolhido=='NÃO ESTA NA LISTA - IREI INFORMAR') {
            $('#dvcblog').css({display:'none'});
            $('#dvtxlog').css({display:'block'});
            $('#txLogra').attr('placeholder','Digite aqui');
        }
    }

    function VeOCep() {
        alert('Após descobrir seu cep retorne a essa pagina');
        window.open('http://www.buscacep.correios.com.br/sistemas/buscacep/buscaCep.cfm', '_blank');
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
<h1>Cadastro</h1>

{{ HTML::ul($errors->all() )}}

{{ Form::open(array('url' => 'pessoa')) }}

<div>
    {{ Form::label('user', 'Usuário') }}
    {{ Form::text('user', Input::old('user'), array('required' => 'required')) }}
</div>

<div>
    {{ Form::label('Nome', 'Nome') }}
    {{ Form::text('Nome', Input::old('Nome'), array('required' => 'required')) }}
</div>

<div>
    {{ Form::label('email', 'Email') }}
    {{ Form::email('email', Input::old('email'), array('required' => 'required')) }}
</div>

<div>
    {{ Form::label('fone', 'Telefone') }}
    {{ Form::text('fone', Input::old('fone'), array('required' => 'required')) }}
</div>

<div>
    {{ Form::label('password', 'Senha') }}
    {{ Form::text('password', Input::old('password'), array('required' => 'required')) }}
</div>

<div>
    {{ Form::label('password', 'Repita a Senha') }}
    {{ Form::text('password', Input::old('password'), array('required' => 'required')) }}
</div>

<div>
    {{ Form::label('remember_token', 'Lembrete de Senha') }}
    {{ Form::text('remember_token', Input::old('remember_token'), array('required' => 'required')) }}
</div>

<!-- -->

<div>
    {{ Form::label('Cep', 'Cep') }}
    <input type="number" max="99999999" min="1" name="txCep" onkeyup="KeyUp()" required id="txCep"">
    <input name="btCep" type="button" onclick="VeOCep()" value="Descobrir o cep">
</div>

<div id="endereco"></div>
<div id="dvLogra" style="display: none">

    <div id="dvtxlog" style='display: none'>
        <label for='txLogra' name='lbtxLogra' id='lbtxLogra' >Endereço</label>
        <input type='text' name='txLogra' id='txLogra'>
        <label for='text'>Número</label>&nbsp;<input type='text' name='txNumeroC' id='txNumeroC'>
    </div>

    <div id="dvcblog" style='display: block'>
        <label for='cbLogra'>Endereço</label>
        <select name="cbLogra" id="cbLogra" data-live-search='true'>
        </select>
        <label for='text'>Número</label>&nbsp;<input type='text' name='txNumeroR' id='txNumeroR'><Br>
    </div>

    <label for='text'>Complemento</label>&nbsp;<input type='text' name='txComplemento' id='txComplemento'>
</div>
<label>
    <p><input type="checkbox" onclick="Aceito();">&nbsp;Aceito os termos de privacidade
    <input name="btPol" type="button" onclick="LePolitica()" value="Ler a política de privacidade"></p>
</label>
<Br>

<button type="submit" name="btSalvar" id="btSalvar" disabled class="btn btn btn-primary">Salvar cadastro</button>

{{ Form::close() }}

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>

@stop