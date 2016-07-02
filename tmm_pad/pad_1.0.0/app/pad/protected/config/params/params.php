<?php
return CMap::mergeArray(
    array(
        
    ),
    file_exists(dirname(__FILE__).'/params-dev.php') ? require(dirname(__FILE__).'/params-dev.php') : array(),
    file_exists(dirname(__FILE__).'/params-local.php') ? require(dirname(__FILE__).'/params-local.php') : array()
);