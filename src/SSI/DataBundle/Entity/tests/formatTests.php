<?php

//require_once('../format.php');
use SSI\DataBundle\Entity\format;

class formatTest extends PHPUnit_Framework_TestCase
{
  public function setUp(){ }
  public function tearDown(){ }

  public function testMapData () {
     $fmt = new format();
     $data = array();
     $fmt->mapData($data);
  }

  /*public function testEmptyMapData () {
     $fmt = new format();
     $data = array();
     $fmt->mapData($data);
  }*/

  /*public function testformatDataPie () {

  }*/
  
}
