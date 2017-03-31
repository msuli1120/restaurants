<?php
  require_once "src/Cuisine.php";

  class ClassTest extends PHPUnit_Framework_TestCase {
    function testClass () {
      //Arrange
      $test_class = new Class;
      $input = "";
      //Act
      $result = $test_class->methode();
      //Assert
      $this->assertEquals(" ", $result);
    }
  }

?>
