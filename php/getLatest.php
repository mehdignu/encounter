<?php

session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
    return false;
}

session_regenerate_id();
include("config.php");
$id = $_POST['id'];




//increment user notiications count with each request
$query = "SELECT messages FROM scheduled WHERE id='$id'";
$result = mysqli_query($connection, $query);


echo json_encode($result->fetch_array()[0], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

