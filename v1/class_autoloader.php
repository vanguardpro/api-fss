<?php

function __autoload($class) {

    $filename = lcfirst($class);
    if (file_exists($filename . ".php")) {
        include_once $filename . ".php";
    } else {
        new Error('File ' . $filename . ' not found', 404);
    }
}

if (function_exists('lcfirst') === false) {

    function lcfirst($str) {
        $str[0] = strtolower($str[0]);
        return $str;
    }

}
?>
