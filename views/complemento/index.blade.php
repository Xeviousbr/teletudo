@extends('layouts.padrao')
 
@section('content')

<h1>Escolha a forma de pagamento<h1>

<a class="btn btn-small btn-success btn-lg btn-block" href="{{ URL::to('vlrtransf/create/') }}">Transferência Bancária</a>

<a class="btn btn-small btn-secondary btn-lg btn-block" disabled href="{{ URL::to('pagamento/') }}">Outras opções</a>

@stop