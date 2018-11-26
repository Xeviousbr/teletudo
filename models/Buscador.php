<?php

class Buscador
{
    function Busca($texto) {

        $ch = curl_init();
        $url = "https://www.google.com.br/search?q=".$texto."&oq=".$texto."&aqs=chrome..69i57j0l5.687j0j8&sourceid=chrome&ie=UTF-8";
        curl_setopt($ch, CURLOPT_URL,  $url);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $Operacao);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $Dados);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $results = curl_exec($ch);
        curl_close($ch);
        var_dump($results);
        die;

        $EndIn = strpos($results, "<h3 class=\"r\">");
        // $EndFi = strpos($results, "/&amp");
        $pedIni = $EndIn+30;
        $pedaco = substr($results, $pedIni, 300);
        $EndFi = strpos($pedaco, "/&amp");
        // echo $pedaco;

        // Separar o nome apartir do pedaço
        $nmIni = strpos($pedaco, "<b>");
        $nmFim = strpos($pedaco, "</a>");
        $nome = substr($pedaco, $nmIni, $nmFim-$nmIni);

        $ender = substr($pedaco, 0, $EndFi);

        // echo $ender.' - '.$nome;

        /* $dados = ['pesquisa' =>
            ['tipo' => 'web', 'resultados', []]]; */

        $dados = ['pesquisa' => "0"];

        $results = json_encode($dados);

        echo $results;

        // var_dump($results);

        die;

        // http://www.tele-tudo.com/operacoes?op=8&texto=Dayse

        return "ok";
    }

}