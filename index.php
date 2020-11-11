<?php

//Configurações de Proxy SENAI
$proxy = '10.1.21.254:3128';

//configuração de proxy
$arrayConfig = array(
   'http' => array(
       'proxy' => $proxy,
       'request_fulluri' => true
   ),
   'https' => array(
       'proxy' => $proxy,
       'request_fulluri' => true),
);
$context = stream_context_create($arrayConfig);
//------------------------------------



$url = "http://gutenberg.org/";
$html = file_get_contents($url, false, $context);

$dom = new DOMDocument();
libxml_use_internal_errors(true);

//Transformando html em objeto
$dom->loadHTML($html);
libxml_clear_errors();

//Capturando as tags P
//$tagsP = $dom->getElementsByTagName('p');
//      foreach ($tagsP as $p) {
//      echo $p->nodeValue;
//                    echo "<br><br/>";
//Captura as tags div
$tagsDiv = $dom->getElementsByTagName('div');
$arrayP = array();


foreach ($tagsDiv as $div) {
   $classe = $div->getAttribute('class');

   if ($classe == 'page_content') {
       $divsInternas = $div->getElementsByTagName('div');

       foreach ($divsInternas as $divInterna) {
           $classeInterna = $divInterna->getAttribute('class');


           if ($classeInterna == 'box_announce') {
               $tagPInternas = $divInterna->getElementsByTagName('p');


               foreach ($tagPInternas as $tagP) {

                   $arrayP[] = $tagP->nodeValue;
                   
                   
               }
               break;
           }
       }
       break;
   }
}

for ($i = 0; $i < count($arrayP); $i++) {
echo '<p>'.($arrayP[$i]) . '</p>';    
}
?>