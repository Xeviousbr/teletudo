<?php
$idUser = 0;
?>
@extends('layouts.padrao')

<style type="text/css">
    .btn-google-plus {
        color:#fff!important;
        background:#d64937;
    }
</style>

@section('content')

<div class="row">
    <div class="col-sm-offset-3 col-sm-6">
        {{ Form::open(array('url' => 'entrar','class'  => 'well')) }}
            <div class="form-group">
                {{ Form::text('user', '', array('class' => 'form-control input-lg', 'autofocus', 'placeholder' => 'Usuario..')) }}
            </div>
            <div class="form-group">
                {{ Form::password('senha', array('class' => 'form-control input-lg', 'placeholder' => 'Senha..')) }}
            </div>
            @if (Session::has('flash_error'))
                <div class="alert alert-danger">Usu�rio ou senha inv�lidos.</div>
            @endif
            <label class="checkbox">
                {{ Form::checkbox('remember', 'remember', true) }} Lembre-se de mim
            </label>
            {{ Form::submit('Entrar', array('class' => 'btn btn-lg btn-primary btn-block')) }}
        {{ Form::close() }}
        <form name="formulario" action="http://www.tele-tudo.com/pessoa/create" method="get">
            {{  Form::submit('Cadastrar', array('class' => 'btn btn-lg btn-success btn-block')) }}
        </form>
        <?php
        $cSes = new Sessao();
        $url = $cSes->urlFace();
        // echo "<a href=".$url." class='btn btn-lg btn-facebook btn-block'>Entrar pelo Facebook</a>";
        ?>
    </div>
</div>
<?php
?>
<script>startApp();</script>
@stop