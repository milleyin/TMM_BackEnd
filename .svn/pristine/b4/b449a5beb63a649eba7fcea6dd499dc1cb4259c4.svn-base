<?php
    header("Content-type:application/json;");
    $return = array();
    $return['code'] = '200';
    $return['errorCode'] = '200';
    $return['version'] = Helper::getIpadVersion();
    $array = json_decode($content, true);
    if ( !empty($array)) {
        $return['content'] = $array;
    } else {
        $return['content'] = $content;
    }
    echo json_encode($return);