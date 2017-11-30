<?php
/**
 * Created by PhpStorm.
 * User: mehdidridi
 * Date: 9/7/17
 * Time: 7:07 PM
 */

include("../php/config.php");

// this function add a request to an event

$data = json_decode($_POST['request']);
$eventID = $data->eventid;
$owner = $data->owner;
$userName = $data->username;

//TODO : before insert a request check if it is already accepted


//increment requester allowed requests
$query = "SELECT allowedReq FROM `users` WHERE `UserName`='$userName'";
$result = mysqli_query($connection, $query);

$row = mysqli_fetch_row($result);
$count = $row[0];

if ($count < 3) {

    $query = "INSERT INTO `requests` (`owner`, `requester`,`eventID`) VALUES ((SELECT `id` FROM `users` WHERE `UserName` = '$owner'), (SELECT `id` FROM `users` WHERE `UserName` = '$userName'),$eventID)";
    $result = mysqli_query($connection, $query);
    if (!$result) {

        echo("Error description: " . mysqli_error($connection));

    }

    //increment user notifications count with each request
    $query = "UPDATE `users` SET ReqCount=ReqCount+1 WHERE `UserName`='$owner'";
    $result = mysqli_query($connection, $query);


    //increment requester allowed requests
    $query = "UPDATE `users` SET allowedReq=allowedReq+1 WHERE `UserName`='$userName'";
    $result = mysqli_query($connection, $query);

}