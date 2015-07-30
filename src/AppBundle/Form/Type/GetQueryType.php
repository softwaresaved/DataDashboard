<?php
/**
*  Class to allow access to the querystore table 
*/

namespace AppBundle\Form\Type;

use SSI\DataBundle\Entity\SSIQueryStore;

class GetQueryType
{
    public function fetchTables ()
    {  
        $results = self::get();
        foreach ($results as $res)
        {
            $tables[] = array('qid' => $res['qid'], 
                              'description' => $res['description'], 
                              'title' => $res['title']
                             );
        } 
        return $tables;
    }

    private function get ()
    {
        $build = new SSIQueryStore();
        return $build->getAll(); 
    }

    public function getHashQuery($hash)
    {
        $build = new SSIQueryStore();
        return $build->get($hash);    
    }

    public function runQuery($querystr)
    {
        $build = new SSIQueryStore();
        return $build->run_query($querystr); 
    }
}

?>
