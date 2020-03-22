<?php

  use Serializable;

  class Offer implements Serializable{

    public $productId;
    public $bodyType;
    public $brand;
    public $model;
    public $name;
    public $description;
    public $odometer; //'mileageFromOdometer'
    public $price;
    public $priceValidUntil;
    public $url;
    
    public function __construct(){}

    public function loadValues(){
      

    } 
    

    public function serialize() {

      return json_encode([
          'productId' => $this->productId,
          'bodyType' => $this->bodyType, 
          'brand' => $this->brand,
          'model' => $this->model,
          'name' => $this->name,
          'description' => $this->description,
          'odometer' => $this->odometer,
          'price' => $this->price,
          'priceValidUntil' => $this->priceValidUntil,
          'url' => $this->url
      ]);
    }

    public function unserialize($serialized) {}
    
  


  }


?>