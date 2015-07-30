<?php

namespace SSI\DataBundle\Entity;

use SSI\DataBundle\Entity\Calculations;

/**
*  Class to expand and run the actual query
*/
class runQuery {
   /**
   *  Function to run the actual 
   */    
   public function run ($data,$structure) {
       $calc = new Calculations();

       $graphdata = array();
       //split into the label for the outgoing key
       // and the data from the incoming data
 
       foreach (json_decode($structure) as $label=> $datum) {
           $graphdata[$label] = $datum;
       }  
       return $graphdata;
   }
}
