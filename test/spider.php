<?php

/**
*   Script to find all href links in a page and return the links
*/

/**
*   Method to get a list of all the hrefs in the page
*/
function listHrefs($initial) {
   if ($initial == null) {
        echo "no starting point";
    }
    
    $links = array();

    $html = file_get_contents($initial, 'r');
    
    $dom = new DOMDocument();

    $dom->LoadHTML($html);
    
    $tagTypes = array('a', 'link','script');
    foreach ($tagTypes as $t) {
        $links = getHrefs($dom->getElementsByTagName($t),$links, $initial);
    }
    # remove empty keys from array and return the link list    
    return array_unique(array_filter($links));
}

/**
*   Method to get the href tags if they exist
*/
function getHrefs($tags,$links, $initial) {

    foreach ($tags as $tag) {
        if ($tag->hasAttribute('href')) {
            $links[] = simpleClean($tag->getAttribute('href'), $initial);
        }
    }
    return $links;
}

/**
*  Wrapper method around str_replace()
*/
function replace ($replaceMe, $baseUrl, $original) {
    return str_replace($replaceMe, $baseUrl, $original);
}

/**
*    simple clean method for variable URLs in the page
*/
function simpleClean ($url,$baseUrl) { 

    if (substr($url,0,3) == '../') {
        return replace('../', $baseUrl . '/', $url);
    }

    if (substr($url,0,2) == './') {
        return replace('./', $baseUrl . '/', $url);
    }
    # no point calling replace, just glue the links together here
    if (substr($url,0,1) == '/') {
        return $baseUrl.$url;
    }

}

/**
*   Script to crawl our links and test if they are all working
*   @author Iain Emsley
*/

$baseUrl = $argv[1];
if (!$baseUrl) {
    print "Usage: php spider.php <urltoscrape>";
}

$href = listHrefs($baseUrl);

foreach ($href as $link) {
     $url = get_headers($link);

     $out = (strpos($url[0], '200')) ? 'Found' : $url[0];

     print "$out: $link\n";
}
?>
