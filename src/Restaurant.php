<?php
  class Restaurant {
    private $id;
    private $name;
    private $location;
    private $cuisine_id;

    function __construct($name, $location, $cuisine_id, $id=null){
      $this->name = $name;
      $this->location = $location;
      $this->cuisine_id = $cuisine_id;
      $this->id = $id;
    }

    function getId(){
      return $this->id;
    }

    function setName($new_name){
      $this->name = (string) $new_name;
    }

    function getName(){
      return $this->name;
    }

    function setLocation($new_location){
      $this->location = (string) $new_location;
    }

    function getLocation(){
      return $this->location;
    }

    function setCuisineId($new_cuisine_id){
      $this->cuisine_id = (int) $new_cuisine_id;
    }

    function getCuisineId(){
      return $this->cuisine_id;
    }

    function save(){
      $executed = $GLOBALS['db']->exec("INSERT INTO restaurants (name, location, cuisine_id) VALUES ('{$this->getName()}', '{$this->getLocation()}', {$this->getCuisineId()});");
      if($executed){
        $this->id = $GLOBALS['db']->lastInsertId();
        return true;
      } else {
        return false;
      }
    }

    static function getRests($id){
      $executed = $GLOBALS['db']->prepare("SELECT * FROM restaurants WHERE cuisine_id = :id;");
      $executed->bindParam(':id', $id, PDO::PARAM_INT);
      $executed->execute();
      $results = $executed->fetchAll(PDO::FETCH_OBJ);
      return $results;
    }

    static function find($id){
      $executed = $GLOBALS['db']->prepare("SELECT * FROM restaurants WHERE id = :id;");
      $executed->bindParam(':id', $id, PDO::PARAM_INT);
      $executed->execute();
      $result = $executed->fetch(PDO::FETCH_ASSOC);
      $new_rest = new Restaurant($result['name'], $result['location'], $result['cuisine_id'], $result['id']);
      return $new_rest;
    }

    function update($new_name, $new_location){
      $executed = $GLOBALS['db']->exec("UPDATE restaurants SET name = '{$new_name}', location = '{$new_location}' WHERE id = {$this->getId()};");
      if ($executed) {
         $this->setName($new_name);
         $this->setLocation($new_location);
         return true;
      } else {
         return false;
      }
    }

    function delete(){
      $executed = $GLOBALS['db']->exec("DELETE FROM restaurants WHERE id = {$this->getId()};");
      if(!$executed){
        return false;
      } else {
        return true;
      }
    }

  }
?>
