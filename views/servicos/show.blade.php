<?php
$idUser = 0;
?>
@extends('layouts.padrao')
<title>
   {{ Lang::get('servicos.titulo').':'.$servico->nome; }}
</title>
@section('content')    
	
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-62348358-1', 'auto');
  ga('send', 'pageview');

</script>

<?php
App::setLocale(Session::get('locale'));

$ifra=0;
if (isset($_GET['iFra'])) {
	$ifra=1;
}

if ($ifra==0)
{
?>

<div class="container">

	<div class="container">
<?php
}
		Session::put('url', $_SERVER ['REQUEST_URI']);
		$ClsCategorias = new Categorias;
		$NomeCat = $ClsCategorias->GetDescricao($servico->categoria);
		$TemTele = $ClsCategorias->GetUsaTele();
?>

<form name="formCat"
    action="http://www.tele-tudo.com"
    method="get">
<?php
if ($ifra==0) {
?>
	<button type="submit" name="btTudo" class="btn-default" >{{ Lang::get('pagination.tudo') }}</button>
<?php
	echo '<button type="submit" name="Cat='.$servico->categoria.'" class="btn-default" >'.$NomeCat.'</button>';
} else {
	echo '<button type="submit" name="Cat='.$servico->categoria.'" class="btn-default" disabled="disabled" >'.$NomeCat.'</button>';
}

$imagem = $servico->imagem;
$texto = $servico->Texto;
$dadosLang = DB::table('dadoslang')
          ->select('banner','texto')       
          ->where('idServ','=',$servico->id)
          ->where('SgLng','=',App::getLocale())
          ->first();          													                   
          
if ($dadosLang != null) {
	$imagem = $dadosLang->banner;
	$texto = $dadosLang->texto;
}

?>

<button type="submit" class="btn-info" disabled="True" >{{ $servico->nome }}</button>
</form>
<h1>{{ $servico->nome }}</h1>
<img alt={{ '"'.$servico->nome.'"'; }} src={{ '"'.$imagem.'"'; }} />

<!-- <?php
Session::put('url', $_SERVER ['REQUEST_URI']);
?>  -->

<h4>Clicks: {{ $servico->clicks+1 }}</h4>
<h1>
<?php
if ($servico->abrangencia==0) {
      echo '<h4>Abrang&ecirc;ncia: Em todo territ&oacute;rio Brasileiro</h1>';
} else {
	if(Auth::check()) {
		echo '<h4>Abrang&ecirc;ncia: '.$servico->abrangencia.' km</h1>';
  }
}

$cep='';
$lat = '';
$lon = '';
if (Session::has('CEP')) {
	$cep = Session::get('CEP');		
	$lat = Session::get('LAT');
	$lon = Session::get('LONG');		
} else {
	if(isset($_COOKIE['jlat'])) {
		$lat = $_COOKIE['jlat'];
		$lon= $_COOKIE['jlon'];				
	}	
}
if ($lat>'') {
		
		$sql = 'select fn_distance('.$lat.', '.$lon.', servicos.lat, servicos.lon) distancia ';
		$sql = $sql.'from servicos where servicos.id = '.$servico->id;
		
		// $distis = DB::select(DB::raw($sql))->toArray();
		$distis = DB::select(DB::raw($sql));
		
		// $dist = $distis[0];
		
		foreach ($distis as $disti) { 
			$dist = $disti->distancia;
		}				
		
		if (Auth::check()) { echo '<font color="#F00000"</p>'.$dist.' Kms</font>'; }
		
		if ($dist<1) {
			echo '<h4><font color="blue">Distância: Menos de Um Kilômetro</font></h4>';
		} else {
			if ($dist>100) {
				echo '<h4><font color="red">Distância: Mais de 100 Km</font></h4>';
			} else {
				if ($dist<2) {
					echo '<h4><font color="blue">Distância: Menos de 2 Kilômetros</font></h4>';
				} else {
					echo '<h4><font color="green">Distância: Aproximadamente '.number_format($dist,0).' kilômetros</font></h4>';
				}
			}
		}	
} else {
	if (Auth::check()) {	
		echo '<font color="#800000>"CEP não informado</font></p>';
	}	
}

echo '<h3>'.Lang::get('servicos.Categoria').' : '.$NomeCat.'</h1>';

if (Auth::check()) {	
	echo '<font color="#F00000"</p>'.$ClsCategorias->GetClicks().' Clicks</font>';
}
         
if (is_null($servico->email)==false) {
      echo '<h5>Email: '.HTML::mailto($servico->email).'</h5>';
}
if (is_null($servico->site)==false) {
      echo '</p>';
      echo '<h4>'.Lang::get('servicos.Site').' '.HTML::link($servico->site).'</h4>';
}
if (is_null($servico->face)==false) {
      echo '<h5>FaceBook: '.HTML::link($servico->face).'</h5>';
} 
if (is_null($servico->Fone)==false) {
      echo '<h5>Fone: '.$servico->Fone.'</h5>';
}
if (is_null($servico->Celula)==false) {
      echo '<h5>'.Lang::get('servicos.Celular').': '.$servico->Celula.'</h5>';
}
if (is_null($servico->Celula2)==false) {
      echo '<h5>'.Lang::get('servicos.Celular').': '.$servico->Celula2.'</h5>';
}
if (is_null($servico->Endereco_ID)==false) {
	$ClsEnderecos = new Enderecos;
	echo '<h5>'.Lang::get('servicos.Endereco').': '.$ClsEnderecos->GetEndereco($servico->Endereco_ID, 0).'</h5>';
} else {
	if (is_null($servico->Bairro_ID)==false) {
		$ClsBairros = new Bairro;
		echo '<h5> '.$ClsBairros->GetBairro($servico->Bairro_ID).'</h5>';	
	} else {
			if (is_null($servico->Cidade_ID)==false) {
			$ClsCidades = new Cidades;
			echo '<h5> '.$ClsCidades->GetCidades($servico->Cidade_ID).'</h5>';			
		}
	}
}

if ($TemTele == 1) {
	
	$ClsCidadesEntregas = new CidadesEntregas;       
	$Entregadora = $ClsCidadesEntregas->GetCidadeEntrega($servico->Cidade_ID, $servico->Bairro_ID);	
		
	$site = '';
	if ($Entregadora>'') {
	
		// So mostrar entregadora se a entregadora do serviço for a mesma da cidade do usuário
		if (Session::has('CID')) { 
			$idCidLoc = Session::get('CID'); 
		} else {
			// echo 'Sem CID na Session</p>';
			$idCidLoc = null; 
		}
		if (Session::has('BAI')) { 
			$idBaiLoc = Session::get('BAI'); 
		} else {
			// echo 'Sem BAI na Session</p>';
			$idBaiLoc = null; 
		}				
						
		$EntrLocal = $ClsCidadesEntregas->GetCidadeEntrega($idCidLoc, $idBaiLoc);
				
		if ($Entregadora == $EntrLocal) {	
			$ClsEntregadoras = new Entregadoras;
			$site = $ClsEntregadoras->GetUrl($Entregadora);
		}
	}
	if ($site>'') {
	?>
<form target="frame_b" method="post" action="<?php echo $site; ?>">
	<div class="alert alert-success">Precisa de Tele-Entrega?
    <input  type="submit" value="Clique aqui" />
  </div>
</form>	
	<?php
	}
}

// echo 'antes</p>';
// if (is_null($servico->Texto)==false) {
	// echo 'is_null = false</p>';
 	if(Auth::check()) {
		// echo 'Auth::check = true</p>';
 		if (Session::get('iduser')==$servico->idpessoa)  {
			// echo 'Session::get('iduser')</p>';
 			?>
 			<a class="btn btn-small btn-success" href="{{ URL::to('servicos/' . $servico->id.'/edit') }}">Editar</a>
 			<?php
		}
	}
     echo '<h4><font color="#800000"</p>'.$texto.'</font></h4>';
//} 
// echo 'depois</p>';

if (Session::has('SERV')==0) {
	$ClsServicos = new Servicos;
	if(Auth::check()==0) {
		$ClsServicos->MarcaClick($servico->id);	 				
	}
}

$Imagens = DB::table('img_serv')
          ->select('imagem','descricao')       
          ->where('serv','=',$servico->id)
          ->get();

foreach ($Imagens as $Imagem) {
	//echo $Imagem->imagem.'</p>';
	echo '<td><a href=""> <img alt="'.$Imagem->descricao.'" src="'.$Imagem->imagem.'"/></td>';
}
          
//if ($TemImg>0) {
//	echo '<div class="alert alert-success">Existem imsgens a mostrar</div>';
//}          
     
  // Gustavo Kuklinski Facebook Integration
  // 1. Comment box per page
  // 2. Like button
?>

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
  $currentUrl  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
  $currentUrl .= ( $_SERVER["SERVER_PORT"] !== 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
  $currentUrl .= $_SERVER["REQUEST_URI"];    
?>

<div class="fb-like" data-href="<?= $currentUrl ?>" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
<br /><br />
<div class="fb-comments" data-href="<?= $currentUrl ?>" data-numposts="5" data-colorscheme="light"></div>
<!-- End Comment box code -->

<?php

if (is_null($servico->youtube)==false) {
	if ($servico->youtube>'') {
		echo "<iframe src='http://youtube.com/embed/".$servico->youtube."' width='640' height='360' frameborder='0' allowfullscreen ></iframe>";
	}
}

?>
@stop