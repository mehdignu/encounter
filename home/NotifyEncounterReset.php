<?php

include("../php/config.php");


$data = json_decode($_POST['NotifyEncounterReset']);
$userName = $data->userName;

//increment user notiications count with each request
$query = "UPDATE `users` SET EncCount=0 WHERE `UserName`='$userName'";
$result = mysqli_query($connection, $query);
if (!$result)
{

    echo("Error description: " . mysqli_error($connection));

}

