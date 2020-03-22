<?php  

/**
 *  Classe Crawler
 *  ===============
 *  Responsável pela varredura da página especificada pela busca no site 'seminovos.com.br'
 *  para obtenção das propriedades de cada uma das ofertas anunciadas. 
 *  Cada uma das ofertas é armazenada no array $_offers, que pode ser então retornado
 *  como um objeto JSON pela API.
 * 
 */

  class Crawler {

    protected $_url;
    protected $_host;
    protected $_params;
    protected $_offers = [];


    public function __construct($url, $params) {
      $this->_url = $url;
      $parse = parse_url($url);
      $this->_host = $parse['host'];
      $this->_params = $params;
    }


    /**
     *  Método principal
     */
    public function run() {
      $url = $this->construct_url();      
      $this->crawl_page($url);
    }


    /**
     * 
     */
    public function crawl_page($url) {        
        list($content, $httpcode, $time) = $this->_getContent($url);
        $this->_processPage($content);
    }


    /**
     *  Constroi a URL para pesquisa no site 'seminovos.com.br'
     *  de acordo com os parâmetros informados no formulário de busca
     *  
     *  Output: url no formato utilizado pelo site
     */
    public function construct_url(){
      $useUrl = $this->_url;
      $params = $this->_params;

      if(isset($params['tipo'])){
        $useUrl .= $params['tipo'] . "/";
      }
      

      if(isset($params['marca'])){
        $useUrl .= $params['marca'] . "/";

        if(isset($params['modelo'])){
          $useUrl .= $params['modelo'] . "/";
        }
      }

      if(isset($params['ano_inicio']) || isset($params['ano_final'])){
        $useUrl .= "ano-";
        if(isset($params['ano_inicio'])){
          $useUrl .= $params['ano_inicio'];
        }
        if(isset($params['ano_final'])){
          $useUrl .= "-" . $params['ano_final'];
        }
        $useUrl .= "/";
      }

      return substr_replace($useUrl ,"",-1);;
    }


    /**
     *  Realiza a varredura dos elementos DOM da página HTML
     *  pesquisada, e armazena as ofertas encontradas no array
     *  $_offers
     * 
     */
    protected function _processPage($content)  {

        $dom = new DOMDocument('1.0');
        @$dom->loadHTML($content);
        $divs = $dom->getElementsByTagName('div');

        $parent_class = 'list-of-cards';
        $list_of_items = [];

        $xpath = new DOMXpath($dom);
        $items = $xpath->query('//div[contains(@class,"schema-items")]');

        foreach ($items as $item){
          $properties = $this->_processItem($item);
          array_push($this->_offers, $properties);
        } 

      }

    
    /**
     *  Recupera as propriedades listadas para o anúncio.
     *  Foram utilizadas as propriedades econtradas na div da classe
     *  'schema-items', que possuem o atributo 'itemprop' 
     */

    protected function _processItem($item){      

      $item_props = [];
      $children = $item->childNodes;      
      $current_attr;

      foreach ($children as $child){
        if ($child->hasAttributes()){          
          foreach ($child->attributes as $attr){            
            if  (strcmp($attr->name, 'itemprop') == 0 ) {              
              $current_attr = $attr->value;              
              switch($attr->value){
                case "productID":
                  $item_props['productID'] = "";
                  break;
                case "url":
                  $item_props['url'] = "";
                  break;
                case "offers":
                  $offer = $this->_processOffer($child);                  
                  $item_props['price'] = $offer['price'];
                  $item_props['priceValidUntil'] = $offer['priceValidUntil'];
                  break;
                default:                  
                  $item_props[$attr->value] = $child->textContent;
              }
            }
            elseif ((strcmp($attr->name, 'content') == 0 )){
              if(empty($item_props[$current_attr])){
                $item_props[$current_attr] = $attr->textContent; 
              }
            }
          }          
        }
      }  
      
      return $item_props;
    }


    /**
     * O preço do anúncio e sua validade ficam em um subnível
     * de atributos do item, sendo necessário acessar o nó filho
     * para a recuperação de tais informações
     */

    protected function _processOffer($domOffer){
      $offer_props = [];
      $children = $domOffer->childNodes;      

      foreach ($children as $child){
        if ($child->hasAttributes()){          
          foreach ($child->attributes as $attr){            
            if  (strcmp($attr->name, 'itemprop') == 0 ) {
              $offer_props[$attr->value] = $child->textContent; 
            }
          }
        }
      }

      return $offer_props;
    }     


    /**
     * Carregamento do conteúdo da página em uma variável para 
     * 
     */

    protected function _getContent($url) {
      
      $handle = curl_init($url);      
        
      // Desligamento da verificação de SSL 
      curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);

      // Opção para retornar o conteúdo carregado        
      curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);

      // Execução do carregamento da url para crawling
      $response = curl_exec($handle);
      $error = curl_error($handle);      

      // Tempo de resposta total
      $time = curl_getinfo($handle, CURLINFO_TOTAL_TIME);      
      $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

      curl_close($handle);
      return array($response, $httpCode, $time);
    }


    
    protected function _printResult($url, $httpcode, $time) {
        ob_end_flush();
        echo "CODE::$httpcode,TIME::$time,URL::$url <br>";
        ob_start();
        flush();
    }


    /**
     *  Método de retorno da API
     */

    public function sendResult(){

      if (empty($this->_offers)){
        http_response_code(404); 
        
        echo json_encode(
          array("message" => "Nenhum produto encontrado.")
        );
      }

      else{
        http_response_code(200);  
        // retorna os anuncios encontrados
        echo json_encode($this->_offers);
      }     
      
    }
  
}

?>