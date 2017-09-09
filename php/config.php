<?php
/**
 * Created by PhpStorm.
 * User: mehdidridi
 * Date: 9/7/17
 * Time: 6:29 PM
 */

$connection = mysqli_connect('localhost', 'mehdi', 'toor');
$connection->query('SET NAMES utf8');
if (!$connection){
    die("Database Connection Failed" . mysqli_error($connection));
}
$select_db = mysqli_select_db($connection, 'encounter');
if (!$select_db){
    die("Database Selection Failed" . mysqli_error($connection));
}