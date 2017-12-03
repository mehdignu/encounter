<?php
/**
 * Created by PhpStorm.
 * User: mehdidridi
 * Date: 9/7/17
 * Time: 7:07 PM
 */
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
    return false;
}

session_regenerate_id();
include("../php/config.php");
include("../php/show.php");


// this function add a request to an event

$data = json_decode($_POST['request']);
$eventID = $data->eventid;
$owner = $data->owner;
$userName = $data->username;

//TODO : before insert a request check if it is already accepted - anyone can change the html -

//check if the maximum number of requests is erreicht
$query = "SELECT `particNum`, `Max` FROM `scheduled` WHERE `id`='$eventID'";
$result = mysqli_query($connection, $query);

$row = mysqli_fetch_row($result);
$count = $row[0];
$max = $row[1];


$UserId = getId($userName);

//check if the user already participating
$query = "SELECT * FROM `participations` WHERE `MemberID`='$UserId' AND `EventID`='$eventID'";
$result = mysqli_query($connection, $query);

$row = mysqli_fetch_row($result);


if ($count < $max && !$row[0]) {


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

} else {
    header('Location: ./home.php');
}