# seminovosbh
Crawler do site Seminovos.com.br

Este projeto contém as seguintes funcionalidades

api/index.php : acesso à api da aplicação, possui apenas um método que aciona a varredura pelo crawler.
api/helpers/Crawler : Classe de varredura da página de resultado do site seminovos.com.br para obtenção de dados dos anúncios
views/search-form : EndPoint para inserção dos dados da busca
views/result: EndPoint para visualização do resultado da busca
