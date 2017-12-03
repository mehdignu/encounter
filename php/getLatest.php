<?php

session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
}

session_regenerate_id();
include("config.php");
$id = $_POST['id'];

$query = "SELECT messages FROM scheduled WHERE id=$id";

$result = mysqli_query($connection, $query);

echo json_encode($result->fetch_array()[0], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

