<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
}

session_regenerate_id();
include("config.php");


$author = str_replace("\"", "", $_POST['author']);

$message = str_replace("\"", "", $_POST['message']);

$dt = str_replace("\"", "", $_POST['dt']);




$arr = array('author' => $author, 'message' => $message, 'dt' => $dt);


$arr = json_encode($arr);

$id = $_POST['id'];

$query = "SELECT messages FROM scheduled WHERE id=$id";

$result = mysqli_query($connection, $query);

$rows = array();

while ($row = $result->fetch_array()) {
    $rows[] = $row;
}

$r = $rows[0]['messages'];

$arr = mysqli_real_escape_string($connection, $arr);

if(!$r){

    $query = "UPDATE scheduled SET messages='$arr' WHERE id=$id";

    $result = mysqli_query($connection, $query);
} else {

    $query = "UPDATE scheduled SET messages= concat(messages,',' ,'$arr') WHERE id=$id";

    $result = mysqli_query($connection, $query);
}









