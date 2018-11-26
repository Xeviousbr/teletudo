<?php $idUser = 0; ?>
@extends('layouts.padrao')
<?php

$debug = 0;
$cep='';
$cat='';
$lat = '';
$long = '';
$LatCons='';
$LonCons='';
$ResultadoGoogle='';
$ClsCategorias = new Categorias;

$ObsIP = '';
if (App::getLocale()=='') {
    $ClsLocation = new Location;
    $ClsLocation->SetaLocal();
    $idPais = $ClsLocation->getidPais();
    $ObsIP = $ClsLocation->GetOpc();
}

// $idPais = 13;
// App::setLocale('fr');

Session::forget('ENTREGA');
if (isset($_GET['geo'])) {

    Session::forget('CEP');
}

if (isset($_REQUEST['CEP'])) {
    $cep = $_REQUEST['CEP'];
    Session::put('CEP', $cep);
    Session::forget('LAT'); Session::forget('LONG');
    Session::forget('CID');
} else {
    Session::forget('CEP');
}

$pag=0;
$achouDebug=0;

foreach($_GET as $key => $val) {
    if ($key != 'btTudo') {
        if (substr($key, 0, 5) == 'btPag') {
            $pag = substr($key, 5);
            if (Session::has('CAT')) {
                $cat = Session::get('CAT');
            }
        } else {
            if ($key == 'btInicio') {
                $pag = '0';
            } else {
                if (substr($key, 0, 3) == "Cat") {

                    $cat = $_REQUEST['Cat'];
                    //$cat = $key;

                    Session::put('CAT', $cat);
                }
            }
        }
    }
}

$face='';
if ($cat>'') {
    $Fcat = substr($cat, 4);
    if ($Fcat == 'ction_types') {
        // Gambiarra pro FaceBook
        $cat = '';
        $face='face ';
    }
    if ($cat>'') {
        if (Session::has('CAT')==0) {
            $ClsCategorias->MarcaClick($cat);
        }
    }
}

if ($cat == '') {
    if (Session::has('CAT')) {
        Session::forget('CAT');
    }
}

?>
<title>
    <?php

    if (App::getLocale()=='pt') {
        if ($cat>'') {
            echo Lang::get('servicos.titulo').' - '.$ClsCategorias->GetDescricao($cat);
        } else {
            echo Lang::get('servicos.titulo');
        }
    }

    $nome = '';
    if (Auth::check()) {
    	$iduser = Auth::id();
        if (Session::has('Nome')) {
            $nome = Session::get('Nome');
        } else {
            $nome = Cookie::get('Nome');
            if ($nome>'') {
                // $iduser = Cookie::get('iduser');
                $cookie = Cookie::make('Nome', $pessoas->Nome);
                $cookie = Cookie::make('iduser', $pessoas->id);
            }
        }
        if ($nome=='') {
            Auth::logout();
        } else {
        	if ($cep=='') {
		        $pessoas = DB::table('pessoa')
		            ->select('Cep')
		            ->where('id','=',$iduser)
		            ->first();
		        $cep=$pessoas->Cep;                	
        	}
        }
    }
    if ($cep=='') {
        if(isset($_COOKIE['jlat'])) {

            // echo 'entrou '; die;
            $lat = $_COOKIE['jlat'];
            $long= $_COOKIE['jlon'];
            Session::put('LAT', $lat);
            Session::put('LONG', $long);
            /*if ($debug>0) { echo 'index.129 Coordenadas pegas pelo Cookie lat = '.$lat.'</p>'; }*/
        }
    }

    if ($lat=='') {
        if (Session::has('LAT')) {
            $lat = Session::get('LAT');
            $long = Session::get('LONG');
            if (Auth::check()) {
                echo 'index.141 Coordenadas pegas pela Session lat = '.$lat.',long = '.$long.' Obtido pela Session</p>';
            }
        }
    }

    // $lat = -6.2297465;
    // $long = 106.829518;

    if (($lat>'') or ($cep>'')) {
        $geo="0";
    } else {
        $geo="1";
    }
    ?>
</title>
@section('content')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=visualization"></script>

<?php
// Chave do tele-tudo/servicos
// AIzaSyDCCMjF6NOUUiFfDxqlnn6d4USOReRnOWY
?>

<script>

    // var x = document.getElementById("demo");

    var Cidade = '';

    function setaCidade(sCidade) {
        Cidade=sCidade;
    }

    function getLocation(fazer) {
        // console.log('Entrou no GetLocation');
        if (fazer>'0') {
            if (navigator.geolocation) {
                // console.log('navigator.geolocation');
                navigator.geolocation.getCurrentPosition(showPosition);
                if (fazer=='2') {
                    document.cookie = "";
                    localStorage.removeItem("location");
                    localStorage.removeItem("CID");
                    document.getElementById("txCep").value="";
                    document.location.assign("http://www.tele-tudo.com/servicos?geo=1");
                }
            } else {
                xinnerHTML = "Geolocation is not supported by this browser.";
            }
        }
    }

    function showPosition(position)
    {
        // console.log('showPosition');
        var jvlat = position.coords.latitude;
        var jvlon = position.coords.longitude;
        document.cookie="jlat =" + jvlat;
        document.cookie="jlon =" + jvlon;
    }

    function Cate(nr) {
        if (Cidade>'') {
            document.location.assign("http://www.tele-tudo.com/servicos?CEP="+Cidade+"&Cat="+nr);
        } else {
            document.location.assign("http://www.tele-tudo.com/servicos?Cat="+nr);
        }
    }

    getLocation(<?php echo $geo; ?>)

</script>

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-62348358-1', 'auto');
    ga('send', 'pageview');
</script>

<?php
if (Auth::check()) {
?>
<form name="formulario"
      action="http://www.tele-tudo.com/servicos"
      method="get">
<?php
}
?>

<h1>{{ Lang::get('servicos.aqui') }}</h1>

<?php
if ($nome>'') {
    echo '<div class="alert alert-success">'.Lang::get('messages.logado').$nome.'</div>';
}
$bairro = '';
$mensagem = '';

if ($cep>'') {
    if ($lat=='') {
        $ClsCep = new Cep;
        $ResultadoGoogle= $ClsCep->GetCoordenadas($cep, 'serv');
        // echo 'ResultadoGoogle = '.$ResultadoGoogle.'<Br>';
        if ($ResultadoGoogle=="OK") {
            $lat = $ClsCep->getLat();
            $long = $ClsCep->getLong();
            $bairro = $ClsCep->GetCidadePelaLoc($lat, $long);

            /*echo 'lat = '.$lat.'<Br>';
            echo 'long = '.$long.'<Br>';
            echo 'bairro = '.$bairro.'<Br>';*/

        } else {
            $mensagem = $ResultadoGoogle;
        }
        echo "<script>setaCidade('".$cep."')</script>";
    }
    $mensagem = Lang::get('messages.mensagemCOMCep');
    $informacao = Lang::get('messages.informacaoCOMCep');

    /*echo 'mensagem = '.$mensagem.'<Br>';
    echo 'informacao = '.$informacao.'<Br>';*/

} else {
    // echo 'SEM CEP</p>'; die;
    Session::forget('CEP');
    $mensagem = Lang::get('messages.mensagemSEMCep');

    if ($lat>'') {
        $ClsCep = new Cep;
        $bairro = $ClsCep->GetCidadePelaLoc($lat, $long);
        $informacao = Lang::get('messages.informacaoSEMcomLAT');
    } else {
        $informacao = Lang::get('messages.informacaoSEMCep');
    }
}
?>
<form name="formulario"
      action="http://www.tele-tudo.com/servicos"
      method="get">
    <p><?php echo $mensagem; ?> : <input name="CEP" id="txCep" type="text" value="<?php echo $cep; ?>" maxlength="30" />
        </a>
        <?php
        Session::put('url', $_SERVER ['REQUEST_URI']);

        echo Form::submit(Lang::get('servicos.Enviar'));
        echo $bairro;

        if (Session::has('CID')) {

            $idCid = Session::get('CID');
            $texto = '</p><font color="red">';
            $quant = 0;
            $QuantMaxAnun=5;

            if ($idCid!='0') {
                $ClsCidades = new Cidades;
                $quant = $ClsCidades->QuantAnuncios($idCid);
                $QuantMaxAnun = $ClsCidades->QuantMaxAnun($idCid);
            }
            $mais = false;
            $linha='';
            if ($quant==0) {
                $texto = $texto.'Na sua cidade não há anúncios</font>';
                $texto = $texto.'<font color="green"><B> Há como colocar '.$QuantMaxAnun.' anúncios gratis para sua cidade</B>';
                $mais = true;
            } else {
                if ($quant==1) {
                    $texto = $texto.'Na sua cidade só tem UM anúncio ';
                    $mais = true;
                } else {
                    $texto = $texto.'Na sua cidade há '.$quant.' anúncios ';
                }
                if ($quant<$QuantMaxAnun) {
                    $anuncio =' anúncios';
                    $um=$QuantMaxAnun-$quant;
                    if ($um==1) {
                        $anuncio =' anúncio';
                    }
                    $texto = $texto.'<font color="green"><B> Há como colocar mais '.$um.$anuncio.' gratis para sua cidade.';
                    $linha='</p>';
                    $mais = true;
                }
            }
            if ($mais) {
                $texto = $texto.' Caso tenha interesse clique <a href="http://www.tele-tudo.com/contatos">AQUI</a></B> ';
            }
            echo $texto.'</font>'.$linha;
            Session::forget('CID');
        }
        ?>
        <!-- will be used to show any messages -->
        @if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
</form>
<?php

if ($lat=='') {
    $ForcarGeo=1;
} else {
    if ($cep=='') {
        $ForcarGeo=0;
    } else {
        if (is_numeric($cep)) {
            $ForcarGeo=0;
        } else {
            $ForcarGeo=1;
        }
    }
}

if ($ForcarGeo==1) {
    Session::put('REDIR', '0');
}

?>

<script Language="JavaScript">
    var cep = <?php echo $cep; ?>;	

    var VersaoNav = navigator.appVersion;
    var Mostrar = true;
    if (VersaoNav.indexOf("Chrome")>0) {
        if (VersaoNav.indexOf("Edge")<1) {
            Mostrar=false;
        }
    }
    if (Mostrar) {
        document.write("<button data-tooltip={{ Lang::get('messages.atualizageo') }} onclick='getLocation(2)'>{{ Lang::get('messages.forcargeo') }}</button>");
    } else {
    	if (cep=='') {
        	document.write("<div class='alert alert-info'>A Geolocalização não funcionará neste navegador, utilize o CEP</div>");
        }
    }
</SCRIPT>

</p>
<table class="table table-striped table-bordered">
    <!--<form name="formCat"
        action="http://www.tele-tudo.com/servicos"
        method="get">-->
    <?php

    $colocarProp = false;
    if ($ObsIP=='F') {
        if ($cat =='') {
            $colocarProp = true;
        }
    }

    if ($colocarProp==true) {
// if (($ObsIP=='F') && ($cat =='')){
        echo '<td><a href="http://www.tele-tudo.com/contatos"> <img alt="Anuncie Aqui" src="http://decorandocasas.com.br/wp-content/uploads/2014/07/ANUNCIE-AQUI.jpg" /></td>';
    } else {
        $LatCons=$lat;
        $LonCons=$long;
        $ClsCategorias->GetCategos($cep, $cat, $lat, $long, $idPais, $debug, $ResultadoGoogle);
        $TemDentro = $ClsCategorias->GetTemDentro();
        $ClsServicos = new Servicos;
        $pgProx=false;
        $QuantCats = 0;

        $servs = $ClsServicos->GetServs($cep, $cat, $lat, $long, $idPais, $pag, $QuantCats);
    // $servs = $ClsServicos->GetServs($cep, $cat, $lat, $long, $idPais, $idsServ, $pag, $QuantCats);
        echo '<div class="alert alert-success">'.$informacao.'</div>';
        if (sizeof($servs)>10) {
            $pgProx=true;
        }
    ?>
    <!--</form>-->
    <?php
    if (($pag>0) || ($pgProx==true)) {
        ?>
        <form name="formPagiIni"
              action="http://www.tele-tudo.com/servicos"
              method="get">
            <tr>
                <td>
                    <?php
                    if ($pag>0) {
                        echo '<button type="submit" name="btInicio" class="btn-default" >'.Lang::get('pagination.inicio').'</button>';
                        echo '<button type="submit" name="btPag'.($pag-1).'" class="btn-default" >Anterior</button>';
                    }
                    ?>
                    <button type="submit" name="'bt1" class="btn-info" disabled="True" >{{ $pag+1 }}</button>
                    <?php
                    if ($pgProx==true) {
                        echo '<button type="submit" name="btPag'.($pag+1).'" class="btn-default" >Pr&oacute;ximo</button>';
                    }
                    ?>
                </td>
            </tr>
        </form>
    <?php
    }
    ?>
    <tbody>
    <?php
    if ($cat>'') {

        if (is_null($ClsCategorias->GetObs())==false) {
            echo '<div class="alert alert-info">'.$ClsCategorias->GetDescricao($cat).': '.$ClsCategorias->GetObs().'</div>';
        }
        echo '<div class="alert alert-success">Clique no banner para ver os detalhes</div>';
    }

    $cont=0;
    $matriz = '';
    $ids = '';
    foreach ($servs as $value) {
        $cont = $cont +1;
        if ($cont<11) {
            if (strrpos($matriz, $value->imagem)==0) {
                $matriz = $matriz.$value->imagem;
                ?>
                <tr>
                    <td>
                        <?php
                        if ($TemDentro == 0) {
                            echo '<div class="alert alert-success">'.Lang::get('servicos.QuemQuiser').' xeviousbr@gmail.com</div>';
                            $TemDentro = 1;
                        }
                        if ($cat=='') {
                            if (($cont == 3) and ($pag==0)) {
                                echo '<div class="alert alert-info">Clique no banner para ver os detalhes</div>';
                            }
                        }
                        if ($value->banner==null) {
                            $imagem = $value->imagem;
                        } else {
                            $imagem = $value->banner;
                        }
                        $ids=$ids.$value->id.',';
                        // echo $ids;
                        ?>
                        <a href="{{ URL::to('servicos/' . $value->id) }}"> <img alt={{ '"'.$value->nome.'"'; }} src={{ '"'.$imagem.'"'; }} /></td>
                    </td>
                </tr>
            <?php
            }
        }
    }
    if ($ids>'') {
        // echo 'entrou';

        // Recurso feito em 13/10/16

        $tamLen = strlen($ids);
        $ids = substr($ids,0,$tamLen-1);
        // echo 'ids = '.$ids;
        $ClsServicos->MarcaCliLista($ids);
    }

    //  MataBug da bug que quando o cep é informado um endereço, não mostra nada na segunda página
    if ($cep!='') {
        if (is_numeric($cep)==false) {
            if ($pgProx==true) {
                ?>
                <tr>
                    <td>
                        <div class='alert alert-info'>Para mostrar mais resultados informe o CEP</div>
                    </td>
                </tr>
            <?php
            }
            $pgProx=false;
        }
    }

    if (($pag>0) || ($pgProx==true)) {
        ?>
        <form name="formPagiFim"
              action="http://www.tele-tudo.com/servicos"
              method="get">
            <tr>
                <td>
                    <?php
                    if ($pag>0) {
                        echo '<button type="submit" name="btInicio" class="btn-default" >'.Lang::get('pagination.inicio').'</button>';
                        echo '<button type="submit" name="btPag'.($pag-1).'" class="btn-default" >'.Lang::get('pagination.previous').'</button>';
                    }
                    ?>
                    <button type="submit" name="'bt1" class="btn-info" disabled="True" >{{ $pag+1 }}</button>
                    <?php
                    if ($pgProx==true) {
                        echo '<button type="submit" name="btPag'.($pag+1).'" class="btn-default" >Pr&oacute;ximo</button>';
                    }
                    ?>
                </td>
            </tr>
        </form>
    <?php
    }
    ?>
    </tbody>
</table>
<?php

echo '<h5>'.Lang::get('messages.duvidas').HTML::mailto('xeviousbr@gmail.com').'</h5>';

?>
<Br><a href="https://chat.whatsapp.com/FKgKLGaK648FLm8zkQG25B"><label>Link do nosso grupo no WhatsApp</label></a>
<?php

// <!-- HTML::mailto(xeviousbr@gmail.com) -->
// <!-- Dúvidas e contatos: HTML::mailto(xeviousbr@gmail.com) -->
// <!-- <a href="{{ URL::to('contato') }}">Contato</a> -->

// Gustavo Kuklinski Facebook Integration
// 1. Comment box per page
// 2. Like button

if ($cat=='') {
    ?>

    </div>
    <!-- Begin Facebook SDK -->
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.3";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
    <!-- End Facebook SDK -->

    <!-- End Comment box code -->
    <?php
    $currentUrl = 'http://www.tele-tudo.com:80';
    ?>
    <div class="fb-like" data-href="<?= $currentUrl ?>" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
    <br /><br />
    <div class="fb-comments" data-href="<?= $currentUrl ?>" data-numposts="5" data-colorscheme="light"></div>
    <!-- End Comment box code -->
<?php
}
if ($lat!=$LatCons) {
    echo '572'; die;
    if (Session::has('REDIR')==0) {
        Session::put('REDIR', '1');
        Redirect::secure(URL::to('/servicos'));
    }	else {
        Session::forget('REDIR');
    }
}

if (Auth::check()) {
    if ($lat!='') {
        echo $lat.','.$long.'</p>';
    } else {
        echo 'Sem coordenadas</p>';
    }
}
}
?>
</div>
@stop