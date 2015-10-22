<?php

//loadData();

function loadData($file, $config) {

if (!$file) {
    throw new Exception('No data file passed');
}

$conf = getConfigFile($config);

$conn = new PDO("mysql:host=" . $conf['host'] . ";dbname=" . $conf['dbname'],
                 $conf['user'],$conf['pass']);

//prepare the statement
$statement = $conn->prepare("INSERT INTO jobs(
             jid, software, softtermin, salary, salarymin, salarymax,  hours, 
             contract, h1, h2, h3, description, typerole,subject,location2)
             VALUES
             (:jid,:software,:softtermin,:salary,:salarymin,:salarymax,:hours,
              :contract,:h1,:h2,:h3,:description,:typerole,:subject,:location2)");

$row=0;

$file_handle = fopen($file, 'r');
if (!$file) {
    throw new Exception('Data file could not be opened ' . $file);
}

$existing_ids = getExistingIds($config);

while (!feof($file_handle) ) {
   
   while (($data = fgetcsv($file_handle, 1000, ",")) !== FALSE) {
    // check that the job id doesn't exist. If not insert.  
     if (!in_array($data[0], $existing_ids)) {
        $row++;
        $statement->execute(
          array(
            'jid' => $data[0], 
            'software' => intval($data[5]), 
            'softtermin' => clean_yorn($data[6]), 
            'salary' => $data[7], 
            'salarymin' => clean_currency($data[8]), 
            'salarymax' => clean_currency($data[9]),
            'hours' => $data[10], 
            'contract' => $data[11], 
            'h1' => $data[15], 
            'h2' => $data[16], 
            'h3' => $data[17], 
            'description' => $data[21], 
            'typerole' => $data[18], 
            'subject' => $data[19],
            'location2' => $data[20]
          )
        );
     }
   }
}

fclose($file_handle);
print "Rows inserted $row";
}

/**
*   Method to get the existing ids into an array
*/
function getExistingIds($config) {
   $results = array();
    try {
        $conf = getConfigFile($config);
        $conn = new PDO("mysql:host=" . $conf['host'] . ";dbname=" . $conf['dbname'],
                 $conf['user'],$conf['pass']);

        //prepare the statement
        $statement = $conn->prepare("SELECT jid FROM jobs");

        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_COLUMN,0);
    } catch (PDOException $pde) {
        echo $pde;
    }
    return array_values($results);
}

/* Data files */

//function to convert Y/N into 1/0
function clean_yorn($incoming) {
    return ($incoming == 'N') ? 0 : 1;
}

function clean_currency($number) {
    //return then integer value of the field, or 0
    $clean_number = floatval(str_replace(',','',trim(substr($number,2))));
    return ($clean_number) ? $clean_number : 0;
}


/**
*   Method to get the config file
*/
function getConfigFile($config_file) {
   $conf = array();

   $conf = parse_ini_file($config_file);

   if (!$conf) {
      throw new Exception('Could not open file');
   }

   return $conf;
}


?>
