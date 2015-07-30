<?php

namespace SSI\DataBundle\Entity;

use SSI\DataBundle\Entity\Calculations;
/**
*
* Adapter class for altering the data
*
*/
class format
{

    /**
    *  Function to map the data structure to the fields
    */
    public function mapData ($data, $structure) 
    {
        $calc = new Calculations();

        $clean_struct = array();   
        foreach (json_decode($structure) as $key => $value)
        {
  
            $ndata = (substr($value,0,9)=='remainder') ? str_replace('remainder(', '',$value) : $value;

            $exarr = explode("(",$ndata,2);

            if (substr($value,0,9) == 'remainder') {
                //calculate the size of the inner function
                $size = call_user_func_array(array($calc, $exarr[0]),array($data, substr($exarr[1],0,-2)));
                //run the new size against the count
                $clean_struct["$key"] = $calc->remainder(sizeof($data), sizeof($size));
            }
            else {
                $term = trim(str_replace("'","", substr($exarr[1], 0, -1)));
                $clean_struct["$key"] = call_user_func_array(array($calc, $exarr[0]),array($data, $term));
                
            }
        }
        return $clean_struct;
    }

    /**
    *  Function to map the data to what the D3 pie chart expects
    */
    public function formatDataPie($data, $structure)
    {
        $jsstr = '';

        //map the data
        $map_data = self::mapData($data, $structure);

        foreach ($map_data as $key => $value) {
 
            $jsstr .= "{ 'label' : '$key', 'value' : '$value' },";
        }
        $jsstr = '['. substr($jsstr, 0, -1) . ']';

        return $jsstr;
    }
    
    /**
    *  Function to map the data in D3 Hash format
    */    
    public function formatDataHist($data, $structure) {
         $jsonstr = '';

         //map the data
         $map_data = self::mapData($data, $structure);
          
         foreach ($map_data as $key => $value) {
            $jsonstr .= "{ 'label' : '$key', 'value' : '$value' },";
         }   

         $jsonstr = '['. substr($jsonstr, 0, -1) . ']';

         return $jsonstr;
    }

    public function formatCSVData () {

    }
   
}
?>
