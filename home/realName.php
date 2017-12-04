<?php

include_once '../php/show.php';
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
    return false;
}

session_regenerate_id();

$auth = $_GET['auth'];


echo getUserName($auth);