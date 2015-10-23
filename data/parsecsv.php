<?php
/**
*  CLI Script to update the jobs database. 
*  Requires 2 arguments:
*  fname = the csv file to load
*  config = the config file 
*
*  @author: Iain Emsley
*/
$fname = '';
$config = '';

if (sizeof($argv) > 1) {
    try {
        $fname = $argv[1];
        if (!$fname) {
            throw new Exception ("No filename given");
            exit();
        }

        $config = $argv[2];
        if (!$config) {
            throw new Exception("No config file given");
            exit();
        }
 
        loadData($fname, $config);
    } catch (Exception $e) {
        print $e;
    }
} else {
   print "Please check the arguments";
   exit();
}

/**
*  Function to load the data
*/
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
            'jid' => clean_non_existent($data[0]), 
            'software' => clean_non_existent(intval($data[5])), 
            'softtermin' =>clean_non_existent(clean_yorn($data[6])), 
            'salary' => clean_non_existent($data[7]), 
            'salarymin' => clean_non_existent(clean_currency($data[8])), 
            'salarymax' => clean_non_existent(clean_currency($data[9])),
            'hours' => clean_non_existent($data[10]), 
            'contract' => clean_non_existent($data[11]), 
            'h1' => clean_non_existent($data[15]), 
            'h2' => clean_non_existent($data[16]), 
            'h3' => clean_non_existent($data[17]), 
            'description' => clean_non_existent($data[21]), 
            'typerole' => clean_non_existent($data[18]), 
            'subject' => clean_non_existent($data[19]),
            'location2' => clean_non_existent($data[20])
          )
        );
     }
   }
}

fclose($file_handle);
print "Rows inserted $row";
}


function clean_non_existent($field) {
   $f = ($field !== null) ? $field : '';
   return $f;
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

/**
*  Function to convert Y/N into 1/0
*/
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
