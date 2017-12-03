<?php

include_once '../php/show.php';
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
    return false;
}

session_regenerate_id();

$auth = $_GET['auth'];

if(preg_match("/[a-z]/i", $auth) || preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $auth)){
    header("Location: ../index.html");
    return false;
}

echo getUserName($auth);