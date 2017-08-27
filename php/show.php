<?php

$GLOBALS['connection'] = mysqli_connect('localhost', 'mehdi', 'toor');
$GLOBALS['connection']->query('SET NAMES utf8');
if (!$GLOBALS['connection']){
    die("Database Connection Failed" . mysqli_error($connection));
}
$select_db = mysqli_select_db($GLOBALS['connection'], 'encounter');
if (!$select_db){
    die("Database Selection Failed" . mysqli_error($connection));
}


function eventsArray(){
    $result =mysqli_query($GLOBALS['connection'],"SELECT * FROM `scheduled`");

    return $result;

}

