<!DOCTYPE html>
<html>
    <head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <title>
        Tele Tudo - Edi&ccedil;&atilde;o do cadastro de servi&ccedil;o da conta : {{ $servico->nome }}
    </title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container">
<div class="container">
<nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
        <li><a href="{{ URL::to('produtos') }}">Produtos</a></li>
        <li><a href="{{ URL::to('/servicos') }}">Servi&ccedil;os</a></li>
        <li><a href="{{ URL::to('sair') }}">Deslogar</a>         
    </ul>
</nav>

<h1>Edit {{ $servico->nome }}</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::model($servico, array('action' => array('ServicosController@update', $servico->id), 'method' => 'PUT')) }}

	<div class="form-group">
		{{ Form::label('nome', 'Texto') }}
		{{ Form::text('Texto', null, array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::label('email', 'Email') }}
		{{ Form::email('email', null, array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('Fone', 'Telefone') }}
		{{ Form::text('Fone', null, array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('Celula', 'Celular') }}
		{{ Form::text('Celula', null, array('class' => 'form-control')) }}
	</div>		
	
	<div class="form-group">
		{{ Form::label('YouTube', 'YouTube') }}
		{{ Form::label('InstruYouTube', ' - Utilize somente a parte que identifica o video') }}
		{{ Form::text('youtube', null, array('class' => 'form-control')) }}
	</div>	

	<input class="btn btn-primary Salvar" type="submit" value="Salvar">

{{ Form::close() }}

</div>
</body>
</html>
