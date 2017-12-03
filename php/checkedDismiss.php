<?php

session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
}

session_regenerate_id();
include("../php/config.php");


$data = json_decode($_POST['checked']);
$userName = $data->userName;

//increment user notiications count with each request
$query = "UPDATE `users` SET Checked=1 WHERE `UserName`='$userName'";
$result = mysqli_query($connection, $query);

if (!$result)
{

    echo("Error description: " . mysqli_error($connection));

}

