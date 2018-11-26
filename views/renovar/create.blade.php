<!DOCTYPE html>
<html>
<head>
	<title>Informar transferência de valor</title>
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

		</ul>
	</nav>

<h1>Informar transferência de valor</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all() )}}

{{ Form::open(array('url' => 'vlrtransf')) }}

    <div class="form-group">
        <h1>
        {{ Form::label('lbVlr', 'Valor transferido '.Session::get('Valor')) }}
        </h1>
    </div>

	<div class="form-group">
		{{ Form::label('lbBco', 'Informe o número do Banco') }}
		{{ Form::text('txBco', Input::old('BCO'), array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::label('lbAge', 'Número da Agência') }}
		{{ Form::text('txAge', Input::old('AGE'), array('class' => 'form-control')) }}
	</div>

    <div class="form-group">
        {{ Form::label('lbEmail', 'Email') }}
        {{ Form::email('txEmail', Input::old('EMAIL'), array('class' => 'form-control')) }}
    </div>


	{{ Form::submit('Registrar o pagamento', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}

</div>
</body>
</html>