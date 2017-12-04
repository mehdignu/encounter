<?php
/**
 * Created by PhpStorm.
 * User: mehdidridi
 * Date: 9/9/17
 * Time: 7:39 PM
 */

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.html");
    return false;
}

session_regenerate_id();

include("../php/config.php");
$data = json_decode($_POST['request']);
$eventID = $data->eventid; //request id actually..

$query = "select requester, eventID from requests where requests.requestID = '$eventID'";
$result = mysqli_query($connection, $query);
if (!$result) {
    echo("Error description: " . mysqli_error($connection));
}

$rows = array();
while ($row = $result->fetch_array()) {
    $rows[] = $row;
}

$requester = $rows[0]['requester'];
$eventId = $rows[0]['eventID'];


$query = "SELECT `Max`, `particNum`  FROM `scheduled` WHERE `id`='$eventId'";
$result = mysqli_query($connection, $query);
$data = mysqli_fetch_assoc($result);
$maximum = $data['Max'];
$numm = $data['particNum'];


if (($maximum - $numm) == 1) {

    //insert into the partisipation table after accepting the request
    $query = "INSERT INTO `participations` (`EventID`, `MemberID`) VALUES ('$eventId', '$requester')";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        echo("Error description: " . mysqli_error($connection));
    }

    //increment user encounter notifications count with each accepted request
    $query = "UPDATE `users` SET EncCount=EncCount+1 WHERE users.id='$requester'";
    $result = mysqli_query($connection, $query);


    //increment the number of the participants
    $query = "UPDATE `scheduled` SET `particNum`=`particNum`+1 WHERE scheduled.id='$eventId'";
    $result = mysqli_query($connection, $query);


    //delete the request
    $query = "delete from requests where requests.eventID = '$eventId'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        echo("Error description: " . mysqli_error($connection));
    }


} else {

    //insert into the partisipation table after accepting the request
    $query = "INSERT INTO `participations` (`EventID`, `MemberID`) VALUES ('$eventId', '$requester')";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        echo("Error description: " . mysqli_error($connection));
    }

    //increment user encounter notifications count with each accepted request
    $query = "UPDATE `users` SET EncCount=EncCount+1 WHERE users.id='$requester'";
    $result = mysqli_query($connection, $query);


    //increment the number of the participants
    $query = "UPDATE `scheduled` SET `particNum`=`particNum`+1 WHERE scheduled.id='$eventId'";
    $result = mysqli_query($connection, $query);


    //delete the request
    $query = "delete from requests where requests.eventID = '$eventId'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        echo("Error description: " . mysqli_error($connection));
    }


    //delete the request
    $query = "delete from requests where requests.requestID = '$eventID'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        echo("Error description: " . mysqli_error($connection));
    }

}


