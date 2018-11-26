<?php
$idUser = 0;
?>
@extends('layouts.padrao')
 
@section('content')
<div class="row">



    <div class="col-sm-offset-3 col-sm-6">

        <form name="entrar" action="http://www.tele-tudo.com/produtos" method="get">

            <div class="form-group">
                {{ Form::text('user', '', array('class' => 'form-control input-lg', 'autofocus', 'placeholder' => 'Usuario..')) }}
            </div>
            
            <div class="form-group">
                {{ Form::password('senha', array('class' => 'form-control input-lg', 'placeholder' => 'Senha..')) }}
            </div>
            
            @if (Session::has('flash_error'))
                <div class="alert alert-danger">Usuário ou senha inválidos.</div>
            @endif
            
            <label class="checkbox">
                {{ Form::checkbox('remember', 'remember', true) }} Lembre-se de mim
            </label>
            
            {{ Form::submit('Entrar', array('class' => 'btn btn-lg btn-primary btn-block')) }}
 
        </form>
    </div>
</div>
@stop