<!DOCTYPE html>
<html>
<head>
	<title>
        Tele Tudo - Contato
	</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
</head>
<div class="container">

<nav class="navbar navbar-inverse">
	<ul class="nav navbar-nav">
		<li><a href="{{ URL::to('produtos') }}">{{ Lang::get('menus.Produtos') }}</a></li>
		<li><a href="{{ URL::to('/servicos') }}">{{ Lang::get('menus.Servicos') }}</a></li>

		@if (Auth::check())
			{{--<li><a href="{{ URL::to('operacoes') }}">{{ Lang::get('menus.Operacoes') }}</a></li>--}}
			<li><a href="{{ URL::to('sair') }}">{{ Lang::get('menus.Deslogar') }}</a>
		@else
			<li><a href="{{ URL::to('login') }}">{{ Lang::get('menus.Login') }}</a>
		@endif
	</ul>	
</nav>
<img src="{{ Lang::get('menus.img') }}"/>

<?php
if (isset($_GET['txNome'])) {			
	$nome = $_GET['txNome']; 
	$email = $_GET['txEmail'];
	$message = $_GET['txAssunto'];
	
	$data = array('name' => $nome,
                'email'=> $email);

        Mail::send('emails.auth.reminder', $data, function($message)
            {
            	
            		$message->from('itunna.dt@gmail.com', 'Itunna');
                $message->to('xeviousbr@gmail.com') ->subject('E-mail de cadastro');      
            	

            });
        echo 'Email Envia'; die;
}

if (App::getLocale()=='') {
	$ClsLocation = new Location;
	$funciona = $ClsLocation->SetaLocal();
	$idPais = $ClsLocation->getidPais();
}

$Texto = '';
$ling=App::getLocale();

$contato = DB::table('Lang')
          ->select('contato')
          ->where('Sigla','=',$ling)
          ->first();
if ($contato != null) {
    $Texto= $contato->contato;
} else {
    $Texto = 'Não Achou';
}
echo $Texto;

?>

	@if (Auth::check())
		</Br></Br>
		<p>Tamb&eacute;m pode me enviar um email utilizando o formul&aacute;rio abaixo </strong></p>
		<form name="formulario"
		action="http://www.tele-tudo.com/contatos"
		method="get">						
		
			<label for="txNome">Nome</label>
			<input type="text" class="form-control" Name="txNome" required></input>
				
			<label for="txEmail">Email</label>
			<input type="email" class="form-control" Name="txEmail" required></input>
							
			<label for="txAssunto">Assunto</label>
			<textarea class="form-control" rows="3" Name="txAssunto" required></textarea>
					
			{{ Form::submit('Enviar'); }}
											
			</div>
			<p>&nbsp;</p>
		</form>
	@endif
</div>    
</body>

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

</html>