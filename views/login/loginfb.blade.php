@extends('layouts.padrao')

@section('content')

<?php
$app_secret = "9559a449eece386b90344842e4514f39";
$app_id = "395697367529746";
$redirect_uri = urlencode("http://www.tele-tudo.com/loginfb");

$code = str_replace("#_=_", "", $_GET['code']);
$api = "https://graph.facebook.com/v2.8/oauth/access_token?client_id=$app_id&redirect_uri=$redirect_uri&client_secret=$app_secret&code=$code";
$get_content = file_get_contents($api);
$json = json_decode($get_content, true);
$access_token = $json['access_token'];
$get_info = "https://graph.facebook.com/me/?fields=email,name,first_name,last_name,id&access_token=$access_token";
$content_info = file_get_contents($get_info);
$info_json = json_decode($content_info, true);

$idFace = $info_json['id'];
$cPes = new Pessoa();
$id = $cPes->IDpeloFace($idFace);
$Nome = $info_json['first_name'];
$idFace = $info_json['id'];
Session::put('idFace', $idFace);
Session::put('first_name', $Nome);
Session::put('faceName', $info_json['name']);
$cSes = new Sessao();
if ($id==0) {

    // Passar obrigatoriedade do endereço para o momento da venda

    // echo "Criar usuário<Br>";
    // echo "Setar usuário logado<Br>";

    if (Auth::check()) {
        echo "<div class='alert alert-success'>".$Nome." agora sua conta esta vinculada ao facebook</div>";
        $idPessoa =  Auth::id();
        $cPes->VinculaFace($idPessoa, $idFace);
        $cSes->IncNrFace();
        ?>
        <script>document.location.assign("http://www.tele-tudo.com");</script>
        <?php
    } else {
        ?>
        <script>document.location.assign("http://www.tele-tudo.com/ender");</script>
        <?php
    }

} else {
    echo "<div class='alert alert-success'>Bem vindo ".$Nome." </div>";
    Auth::loginUsingId($id);
    Session::put('Nome',$Nome);
    Session::put('iduser',$id);
    $cSes->IncNrFace();
    if (Session::has('PEDIDO')) {
        $str = "posface?User=".$id.
            '&Ped='.Session::get('PEDIDO').
            '&Tpe='.Session::get('TpEntrega').
            '&Tes='.Session::get('Teste');
    } else {
        if ($id==1) {
            // VER SE É ADM
            $str = "adm";
        } else {
            $cForn = new Fornecedor();
            // VER SE É FORNECEDOR
            if ($cForn->SetIdPessoa($id)) {
                $str = "fornecedor";
            } else {
                $str = "/";
            }
        }
        // $str = "/";
    }
    ?>
    <script>
        document.location.assign("{{$str}}");
    </script>
    <?php
}
?>
@stop