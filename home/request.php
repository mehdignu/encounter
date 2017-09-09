<?php
/**
 * Created by PhpStorm.
 * User: mehdidridi
 * Date: 9/7/17
 * Time: 7:07 PM
 */

include("../php/config.php");


$data = json_decode($_POST['request']);
$eventID = $data->eventid;
$owner = $data->owner;
$userName = $data->username;

$query = "INSERT INTO `requests` (`owner`, `requester`,`eventID`) VALUES ((SELECT `id` FROM `users` WHERE `UserName` = '$owner'), (SELECT `id` FROM `users` WHERE `UserName` = '$userName'),$eventID)";
$result = mysqli_query($connection, $query);
if (!$result)
{

    echo("Error description: " . mysqli_error($connection));

}

