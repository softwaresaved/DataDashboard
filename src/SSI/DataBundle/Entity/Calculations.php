<?php
/**
*  Calculation functions for the graphs
*/
namespace SSI\DataBundle\Entity;

class Calculations
{
   function run($func) {
       return $func;
   }

   function criteria_greater($min)
   {
       return function($item) use ($min) {
           return $item > $min;
       };
   }

   function criteria_less($max)
   {
       return function($item) use ($max) {
           return $item < $max;
       };
   }
   
   function criteria_true ($item) {
       return ($item == 1 || $item === True);
   }


   /**
   *  Function to filter an array by key for a true value
   */
   function filterTrue($data,$term) {
       $termcount=0;

       $term = trim(str_replace("'","",$term));

       foreach ($data as $d) {
           if ($d[$term] == 1 || $d[$term] === True) {
               $termcount++;
           }
       }

       return $termcount;
    }

   /**
   *  Function to filter an array by a criteria
   */
   function filterEqual($data,$term) {
       $termcount=0;
       foreach ($data as $d) {
           if ($d[$term] == 1 || $d[$term] === True) {
               $termcount++;
           }
       }
       return $termcount;
    }

   /**
   *  Function to filter an array by a criteria and a given
   *  operation
   */
   function filter($data,$term) {

       $termcount  ='[';
       foreach ($data as $d) {

           if ($d[$term] > 0) {
               $termcount .= $d[$term] . ',';
           }
       }

       return substr($termcount, 0, -1) . ']';
    }
   
   /**
   *  Function to return the size of items in data array
   */
   function count ($data) {
       return sizeof($data);
   }
   
   /**
   *  Function to return the minimum
   */
   function minimum($data, $term) {
       return min($this->_array_search($data, $term));
   }

   /**
   *  Function to return the max number
   */
   function maximum($data, $term) {
       return max($this->_array_search($data,$term));
   }

   /**
   *  Function to calculate the remainder
   */
   function remainder ($total, $count) {
       return $total - $count;
   }
    /**
    *   Reduce array to a particular entry
    */  
    private function _array_search($data, $searchterm) {
        $results = array();

        foreach ($data as $term => $value) {

            if ($value[$searchterm] && $value[$searchterm] > 0.00){
                $results[] = $value[$searchterm];
            }   
        }
   
        return $results;
    }
}
?>
