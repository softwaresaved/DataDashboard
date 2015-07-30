<?php
/**
*  Class for params manipulation
*/

namespace SSI\DataBundle\Entity;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class SSIQueryDAO {

    /**
    *  Function to load the given file name and return a PHP array
    */
    public function load($resource, $type = null) {

         $yamlUserFiles = $this->locate($resource);
         return $this->parseData($yamlUserFiles);
    }

    /**
    * Extended function to check the file correct
    */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
    }

    /**
    *  Parse Yaml into a PHP array for access
    */
    private function parseData($data) {
         return Yaml::parse($data);
    }

    /**
    * Function to load the locate and return the given file
    */
    private function locate($fname) {

        $configDirectories = array(__DIR__.'/../../../../app/config/');

        $locator = new FileLocator($configDirectories);
        $yamlUserFiles = $locator->locate($fname.'.yml', null, true);
        return $yamlUserFiles;
    }
}

?>
