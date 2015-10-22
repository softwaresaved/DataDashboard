<?php

include "parsecsv.php";

class TestParseCSV extends PHPUnit_Framework_TestCase 
{

    function setUp() {
        $fh = fopen('config.ini', 'w+');
        $fakeString = "user = user\npass = pass\ndbname = data\nhost = local";
        fwrite($fh, $fakeString);
        fclose($fh);

        $fake = array("ATW913, '',''", "ATEST,hmh,gggg");
        $f = fopen('data.csv', 'w+');
        foreach ($fake as $line)
        {
            fputcsv($f,explode(',',$line));
         }
        fclose($f);
    }
    
    function tearDown() {
        unlink('config.ini');
        unlink('data.csv');
    }

    function testYorN () {
        $this->assertEquals(clean_yorn("B"), 1);
    }

    function testYorNo () {
        $this->assertEquals(clean_yorn("N"), 0);
    }

    function testConvertCurrency() {
        $this->assertEquals(clean_currency("£22,685"),22685);        
    }

    function testConvertCurrencyPence() {
        $this->assertEquals(clean_currency('£47,132.47'),47132.47);
    }

    function testConvertCurrencyString() {
        $this->assertEquals(clean_currency('Unspecified'),0);
    }
   
    /**
    * @expectedExceptionMessage Filename cannot be empty!
    */ 
    function testEmptyOpenConfig() {
        $f = "";
        $fh = getConfigFile($f);
        $this->assertEquals($fh, array());
    }
    
    /**
    * @expectedExceptionMessage Could not open file
    */
    function testEmptyOpenConfigException() {
        $f = "nothere.ini";
        $fh = getConfigFile($f);
        $this->assertEquals($fh, array());
    }

    function testOpenConfig() {
        $f = "config.ini";
        $fh = getConfigFile($f);
        $this->assertNotEquals($fh, array());
        $this->assertEquals($fh['user'], 'user');
    }

    /**
    *  @expectedExceptionMessage Exception: No data file passed
    */
    function testLoadDataFile() {
        $ld = loadData('', "config.ini");
    }

    /**
    *  @expectedException Exception
    */
    function testLoadDataFileEmpty() {
        $ld = loadData('', "config.ini");
    }

    /**
    *  @expectedException PDOException
    */
    function testGetIdsEmptyConfig() {
        $ids = getExistingIds('config.ini');
        var_dump($ids);
        $this->assertNull($ids);
    }

    function testGetIdsConfig() {
        $ids = getExistingIds('mysql.ini');
        $this->assertNotNull($ids);
        $this->assertTrue(in_array('ATW913', $ids));
        $this->assertFalse(in_array('ATEST', $ids));
    }
}

?>
