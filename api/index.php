<?php
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: GET,POST");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  require __DIR__.'/helpers/Crawler.php';  
  

  // URL do site para crawling
  $url = "https://seminovos.com.br/";  


  $data = json_decode(file_get_contents('php://input'), true);  

  // Cria uma instancia do crawler para os paramêtros especificados 
  // no formulário de busca
  $crawler = new Crawler($url, $data);
  $crawler->run();

  // Retorna a resposta http da pesquisa com os seguintes códigos
  //  200: pesquisa ok
  //  404: nenhum resultado encontrado para os parâmetros enviados
  $crawler->sendResult(); 

?>