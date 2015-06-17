<?php

namespace SSI\DataBundle\Entity;

class QueryExpansion {

    /**
    *  Function to validate incoming query
    * 
    */
    public function checkQuery($query) {
        $acceptable  = array('filter', 'filterequal', 'filtertrue', 
                              'count', 'remainder');

        if (in_array($query, $acceptable)) {
           return true;
        }
        return false;
    }

    /**
    *  Expand the query into system function
    */
    public function expandQuery ($userfunction) {
        $functions = explode(":",$userfunction);
        var_dump($functions);  
        switch($functions[0]) {
            case 'count':
                return 'count($data)';
                break;
            case 'remainder':
                //extract the function to be remaindered from the command
                $rem = substr($functions[1],1) . ':' . substr($functions[2],0,-1);
                // call self to expand the inner function
                return 'remainder(' . self::expandQuery($rem) . ')';
                break;
            case 'min':
               return 'minimum' . $functions[1];
               break;
            case 'max':
               return 'maximum' . $functions[1];
               break;
            case 'filtertrue':
                //hacky but formats function correctly
                return 'filterTrue' . $functions[1];
            case 'filter':
            case 'filterequal':
            default:
                return 'Unknown function';
                break;

        }
    }
    
}

?>
