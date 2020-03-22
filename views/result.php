
<div class="container">  
  <h2> Resultado da Busca </h2>

  <div class="grid">

<?php

  $actualHost = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
  $apiUrl = $actualHost . "/seminovosbh/api/index.php";

  $marca = $_POST['marca'];
  $modelo = $_POST['modelo'];
  $tipo = $_POST['tipo'];
  $ano_inicio = $_POST['ano_inicio'];
  $ano_final = $_POST['ano_final'];

  $params = [
    "marca" => $marca,
    "modelo" => $modelo,
    "tipo" => $tipo,
    "ano_inicio" => $ano_inicio,
    "ano_final" => $ano_final
  ];

  $postData = json_encode($params); 
  $response = httpPost($apiUrl, $postData);
  
  
  /**
   *  Método cliente para a execução da chamada
   *  ao método POST da API para retorno do resultado da busca
   * 
   */
  function httpPost($url, $data) {  

    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    
    $json_response = curl_exec($curl);
    
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
   
    curl_close($curl);    
    $response = json_decode($json_response, true);

    return $response;
  
  }

?>
    <p> Resultado em Formato de Texto do JSON </p>
    
    <?php print var_dump($response); ?>

  </div>
</div>