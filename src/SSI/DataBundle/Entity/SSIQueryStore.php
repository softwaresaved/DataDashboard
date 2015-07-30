<?php
/**
*  Function to store the query
*/


namespace SSI\DataBundle\Entity;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class SSIQueryStore
{

    public function __construct() 
    {
        $config = new \Doctrine\DBAL\Configuration();

        $dao = new SSIQueryDAO();
        $params = $dao->load('parameters');
        
        $connectionParams = array(
            'user'     => $params['parameters']['database_user'],
            'driver'   => $params['parameters']['database_driver'],
            'password' => $params['parameters']['database_password'],
            'dbname'   => 'ssi',
            'host'     => $params['parameters']['database_host'],
        );

        $this->conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
    }

    public function store ($hash, $query, $vis, $text, $title, $datastruc)
    {
        $this->conn->insert('querystore', array('qid'=>$hash, 
                                                'query'=>$query, 
                                                'vis'=>$vis, 
                                                'description' => $text, 
                                                'title' => $title, 
                                                'datastruc' => $datastruc));
    }

    /**
    *  Function to get the stored query and the type of visualisation 
    */
    public function get ($hash)
    {
        $storedq = $this->conn->fetchAssoc('SELECT query,vis,datastruc 
                                            FROM querystore 
                                            WHERE qid = ?', array($hash));
        if (!$storedq) {
            $storedq = array('query'=>'none', 'vis'=>'none');
        }
        return $storedq;
    } 

    /**
    *  Function to run the query
    */
    public function run_query($querystr)
    {
       $all_rows = $this->conn->fetchAll($querystr);
       return $all_rows;
    }

    /**
    *  Function to get a listing of queries
    */
    public function getAll()
    {
       $all_store = $this->conn->fetchAll('SELECT * FROM querystore');
       return $all_store;
    }
}
?>
