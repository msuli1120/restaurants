<?php
  class Cuisine {
    private $id;
    private $name;

    function __construct($name, $id=null){
      $this->name = $name;
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

    function save(){
      $executed = $GLOBALS['db']->exec("INSERT INTO cuisines (name) VALUES ('{$this->getName()}');");
      if($executed){
        $this->id = $GLOBALS['db']->lastInsertId();
        return true;
      } else {
        return false;
      }
    }

    static function getAll(){
      $return = $GLOBALS['db']->query("SELECT * FROM cuisines;");
      $results = $return->fetchAll(PDO::FETCH_OBJ);
      return $results;
    }

    static function find($id){
      $return = $GLOBALS['db']->prepare("SELECT * FROM cuisines WHERE id=:id;");
      $return->bindParam(':id', $id, PDO::PARAM_STR);
      $return->execute();
      $result = $return->fetch(PDO::FETCH_ASSOC);
      $new_cuisine = new Cuisine($result['name'],$result['id']);
      return $new_cuisine;
    }
  }
?>
