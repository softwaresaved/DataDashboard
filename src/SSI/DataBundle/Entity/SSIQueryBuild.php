<?php
/**
*  Functions to build and run the query
*/

namespace SSI/DataBundle/Model;

class QueryBuild
{
    private string $conn;

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
    /**
    *  Function to build the query
    */
    public function BuildQuery($data) 
    {
        $querystr = self::_build_query($data);
        $rows = $conn->fetchAll($querystr);
        return $rows;
    }
    
    /**
    *  Function to build the query
    *  @param: Array $data
    *  @todo: add in the measures or where clause
    */
    private _build_query($data)
    {
       $sql = "SELECT ";

       $sql .= self::_build_query_field($data['table']['fields']);
    
       $sql .= " FROM " . $data[table];

       return $sql;
    }

    private _build_query_field ($data)
    {
       $sql  = '';
       foreach ($d as $data)
       {
           $sql .= "'" . $d . "',";
       }
       return substring($sql, 0, -1);
    }
}
?>
