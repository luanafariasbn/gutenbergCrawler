<?php

class GuterbergCrawler {
    
    
    private $url;
    private $proxy;
    private $dom;
    private $html;
    
    public function __construct() {
        //Seta valores das variáveis
        $this -> url ="http://gutenberg.org/";
        $this -> proxy = "10.1.21.254:3128";
        $this -> dom = new DomDocument();
       }
       
       public function getParagrafos(){
           $this->carregarHtml();
           $tagsDiv = $this->capturarTagsDivGeral();
           $divsInternas = $this->capturarDivsInternasPageContent($tagsDiv);
           $tagsPInternas = $this->capturarTagsP($divsInternas);
           $arrayParagrafos = $this->getArrayParagrafos($tagsPInternas);
           return $arrayParagrafos; 
        }
    
    private function getContextoConexao(){
        
        $proxy = '10.1.21.254:3128';

//configuração de proxy
$arrayConfig = array(
   'http' => array(
       'proxy' => $this->proxy,
       'request_fulluri' => true
   ),
   'https' => array(
       'proxy' => $proxy,
       'request_fulluri' => true),
);
$context = stream_context_create($arrayConfig);
return $context;
    }
    
    private function carregarHtml() {
        $context = $this->getcontextoConexao();
        $this->html = file_get_contents($this->url, false, $context);
        
        libxml_use_internal_errors(true);
        
        $this->dom->loadHTML($this->html);
        libxml_clear_errors();
    }
    
    private function capturarTagsDivGeral(){
        
        $tagsDiv = $this->dom->getElementsByTagName('div');
        return $tagsDiv;
    }
    
    private function capturarDivsInternasPageContent($divsGeral){
        
        $divsInternas = null;
        foreach ($divsGeral as $div) {
         $classe = $div->getAttribute('class');
         
         if ($classe == 'page_content') {
              $divsInternas = $div->getElementsByTagName('div');
              break;
         }
        }
        return $divsInternas;
    }
    
    private function capturarTagsP($divsInternas){
        
        $tagPInternas = null;
          foreach ($divsInternas as $divInterna) {
           $classeInterna = $divInterna->getAttribute('class');


           if ($classeInterna == 'box_announce') {
               $tagPInternas = $divInterna->getElementsByTagName('p');
      
       }
    }
    return $tagPInternas;
  }
  
  private function getArrayParagrafos($tagsPInternas){
      
      $arrayParagrafos = [];
      foreach ($tagsPInternas as $tagP){
          $arrayParagrafos [] = $tagP->nodeValue;
        }
       return $arrayParagrafos; 
  }
}