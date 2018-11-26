<?php
Session::put('SemLogo', 1);
?>
<script language="JavaScript">
function fechar(){
    window.opener = window;
    window.close("#");
    // history.go(-1);
}
</script>
@extends('layouts.padrao')
<title>Política de privacidade do site Tele-Tudo</title>
@section('content')
    <h2>Política de privacidade para <a href='http://tele-tudo.com'>Tele-Tudo</a></h2>
    <p>Todas as suas informações pessoais recolhidas, serão usadas para o ajudar a tornar a sua visita no nosso site o mais produtiva e agradável possível além de necessária para o funcionamento da tele-entrega, conforme a modalidade de venda.</p>
    <p>A garantia da confidencialidade dos dados pessoais dos utilizadores do nosso site é importante para o Tele-Tudo.</p>
    <p>Todas as informações pessoais relativas a membros, assinantes, clientes ou visitantes que usem o Tele-Tudo serão tratadas em concordância com a Lei da Proteção de Dados Pessoais de 26 de outubro de 1998 (Lei n.º 67/98).</p>
    <p>A informação pessoal recolhida pode incluir o seu nome, e-mail, número de telefone e/ou celular, endereço, data de nascimento eoutros.</p>
    <p>O uso do Tele-Tudo pressupõe a aceitação deste Acordo de privacidade. A equipe do Tele-Tudo reserva-se ao direito de alterar este acordo sem aviso prévio. Mas se o fizer, será informado por email.</p>
    <h2>Os Cookies e Web Beacons</h2>
    <p>Utilizamos cookies para armazenar informação, tais como as suas preferências pessoas quando visita o nosso website. Isto poderá incluir um simples popup, ou uma ligação em vários serviços que providenciamos, tais como fóruns.</p>
    <p>Você detém o poder de desligar os seus cookies, nas opções do seu browser, ou efetuando alterações nas ferramentas de programas Anti-Virus, como o Norton Internet Security. No entanto, isso poderá alterar a forma como interage com o nosso website, ou outros websites. Isso poderá afetar ou não permitir que faça logins em programas, sites ou fóruns da nossa e de outras redes.</p>
    <h2>Ligações a Sites de terceiros</h2>
    <p>O Tele-Tudo possui ligações para outros sites, os quais, a nosso ver, podem conter informações / ferramentas úteis para os nossos visitantes. A nossa política de privacidade não é aplicada a sites de terceiros, pelo que, caso visite outro site a partir do nosso deverá ler a politica de privacidade do mesmo.</p>
    <p>Não nos responsabilizamos pela política de privacidade ou conteúdo presente nesses mesmos sites.</p>
    <div style="text-align: center;">
        <button type="button" onclick="fechar();" class="btn btn-primary">Concordo com os termos</button>
        {{--<button type="button" onclick="history.back();" class="btn btn-danger">Não concordo com os termos</button>--}}
        <Br><Br>
    <div>
@stop