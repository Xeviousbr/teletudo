<?php
$idUser=0;
?>
@extends('layouts.padrao')
<title>NOVA PAGINA</title>
@section('content')

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/1.4.5/numeral.min.js"></script>
    <?php
    // $mostrar=false; XXX
    $adm=false;
    $cep='';
    $pesq='';
    $req = '';
    $qtItens=0;
    Session::put('url', $_SERVER ['REQUEST_URI']);
    $Teste = 0;
    $logado=0;
    $Perto = Lang::get('produtos.perto');
    ?>
    <h2>Aqui você encontra produtos por tele-entrega</h2>
    <h3 class="text-info">
        <?php
        if ($adm==true) {
            $mensagem = Lang::get('messages.caso');
        }
        else {
            $mensagem = Lang::get('messages.informe');
        }
        echo Lang::get('produtos.Quetal');
        $Tpe=0;
        echo '</h3>';
        ?>
        <form name="formulario" action="http://www.tele-tudo.com/produtos" method="get">
            <p><?php echo $mensagem; ?> : <input name="{{ Lang::get('messages.cep') }}" type="text" autofocus value="<?php echo $cep; ?>" enabled="false" <?php echo $req; ?> />
            <p>{{ Lang::get('produtos.informerc') }}
                <input name="PESQ" type="search" results="10" value = "<?php echo $pesq; ?>" enabled="false" required placeholder="{{ Lang::get('messages.escreva') }}" />
                <input name="Tpe" type="hidden" value="<?php /*echo $Tpe; */?>">
                <button type="submit" class="btn btn-success">Pesquisar</button>
            @if (Session::has('message'))
                <div class="alert alert-info"><h3>{{ Session::get('message') }}</h3></div>
            @endif
            <Br>
            <input type="button" id="btLogin" value="Login" onclick="Logar()" class="btn btn-primary " />
            <input type="button" id="btCadastrar" value="Cadastrar" onclick="Cadastrar()" class="btn btn-success " />
            <a href='' class='btn btn-facebook '>Entrar pelo Facebook</a>
            <?php
            $idPed=0;
            $idForn=0;
            echo '</form>';
            echo '<h5>'.Lang::get('messages.duvidas').HTML::mailto('xeviousbr@gmail.com').'</h5>';
            ?>
            <a href="https://chat.whatsapp.com/FKgKLGaK648FLm8zkQG25B"><label>Link do nosso grupo no WhatsApp</label></a>
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
@stop