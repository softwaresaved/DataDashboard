<?php
/**
*  Function to run the meta queries
*/

namespace SSI\DataBundle\Entity;

use Symfony\Component\Config\Loader\FileLoader;
use \Doctrine\DBAL\Connection;

class SSIQueryMeta
{
    private $conn;

    public function __construct(){

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
    /**
    *  Function to get the table metadata
    */
    public function get_table_meta()
    {
       $meta  = Array();
       $tables = self::run(self::get_tables());
       
       foreach ($tables as $t)
       {
          if ($t['Tables_in_ssi'] != "querystore") {
              $nonmeta = self::_access_fields($t['Tables_in_ssi'], self::run(self::get_field($t['Tables_in_ssi'])));
              $meta = array_merge($meta, $nonmeta);
          }
       }
       return $meta;
    }

    /**
    *  Function to access and return the fields for the query form
    */
    private function _access_fields($db, $tables)
    {
        $accum_fields = Array();
        foreach ($tables as $table)
        {
            $accum_fields[] = array($db.".".$table['Field'] => "($db) ". $table['Field']);
        }
        return $accum_fields;
    }

    /**
    *  Function to run the query
    */
    private function run ($query)
    {
       //run the query - how does Doctrine do this?
       $rows = $this->conn->fetchAll($query);
       return $rows;
    }
    /**
    *  Function to get the tables from the SSI database
    */
    private function get_tables()
    {
       $sql =  "SHOW TABLES FROM ssi";
       return $sql;
    }

    /**
    *  Function to get the fields from the table
    */
    private function get_field($table)
    {
          $sql = "SHOW FIELDS FROM $table";
       
       return $sql;
    }
}
