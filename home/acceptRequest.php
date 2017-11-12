<?php
/**
 * Created by PhpStorm.
 * User: mehdidridi
 * Date: 9/9/17
 * Time: 7:39 PM
 */


include("../php/config.php");
$data = json_decode($_POST['request']);
$eventID = $data->eventid; //request id actually..

$query = "select requester, eventID from requests where requests.requestID = '$eventID'";
$result = mysqli_query($connection, $query);
if (!$result)
{
    echo("Error description: " . mysqli_error($connection));
}

$rows = array();
while ($row = $result->fetch_array()) {
    $rows[] = $row;
}

$requester = $rows[0]['requester'];
$eventId = $rows[0]['eventID'];


//insert into the partisipation table after accepting the request
$query = "INSERT INTO `participations` (`EventID`, `MemberID`) VALUES ('$eventId', '$requester')";
$result = mysqli_query($connection, $query);
if (!$result)
{
    echo("Error description: " . mysqli_error($connection));
}

//increment user encounter notifications count with each accepted request
$query = "UPDATE `users` SET EncCount=EncCount+1 WHERE users.id='$requester'";
$result = mysqli_query($connection, $query);



//delete the request
$query = "delete from requests where requests.requestID = '$eventID'";
$result = mysqli_query($connection, $query);
if (!$result)
{
    echo("Error description: " . mysqli_error($connection));
}



