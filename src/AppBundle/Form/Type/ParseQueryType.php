<?php
/**
*  Function to parse the incoming data from the forms
*    
*/

namespace AppBundle\Form\Type;

use SSI\DataBundle\Entity\SSIQueryStore;
use SSI\DataBundle\Entity\QueryExpansion;

class ParseQueryType
{
    public function ParseQuery ($data)
    {
        $_tmpq = array_keys(self::create_data_struc($data['Fields']));
        
        $tables = "";
        foreach ($_tmpq as $table)
        {
            if ($table != 'place') {
                $tables .= "$table $table";
            }
        }

        $query = "SELECT " . self::create_str($data['Fields']) . " FROM $tables";

        if (sizeof($_tmpq) > 1) {
            $query .= " JOIN place place ON place.lid and jobs.lid";  
        }
        $labels = json_encode(self::createLabels($data['Data']));

        $build = new SSIQueryStore();

        $build->store(self::create_hash($query . $data['Visual']), 
                                        $query, 
                                        $data['Visual'], 
                                        $data['Description'], 
                                        $data['Title'],  
                                        $labels); 
    }

    /**
    *  Create the hash id from the query string
    */
    private function create_hash($querystr)
    {
        return md5($querystr);
    }

    /**
    *  Create the fields section of the query string
    */
    private function create_str ($field)
    {
        $field_str="";
        foreach ($field as $f)
        {
            $field_str .= "$f,";
        }
        return substr($field_str, 0, -1);
    }

    private function create_data_struc ($data)
    {
        $dataarr= Array();
        foreach ($data as $d)
        {
            $_tmp = explode('.', $d);
            $dataarr[$_tmp[0]][] = $_tmp[1];
        }
        return $dataarr;
    }

    /**
    *  Function to create the data labels
    */
    private function createLabels ($formdata) {
        $ex = new QueryExpansion();
        $labels = array();
        //create array of the exploded fields
        $fields=explode(',',$formdata);
        //iterate across the fields to 
        foreach ($fields as $field) {
            $dbfield = explode('=',$field);
            $labels[$dbfield[0]]=$ex->expandQuery($dbfield[1]);
        }
        return $labels;
    }
}

?>
