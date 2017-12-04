<?php
/**
 * Created by PhpStorm.
 * User: mehdidridi
 * Date: 9/9/17
 * Time: 7:38 PM
 */
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
    return false;
}

session_regenerate_id();
include("../php/config.php");
$data = json_decode($_POST['request']);
$eventID = $data->eventid;

//get the requester ID and decrement allowed requests
$query = "SELECT requester FROM `requests` WHERE requests.requestID = '$eventID'";
$result = mysqli_query($connection, $query);

$row = mysqli_fetch_row($result);
$requesterID = $row[0];


//delete request
$query = "delete from requests where requests.requestID = '$eventID'";

$result = mysqli_query($connection, $query);

if (!$result)
{

    echo("Error description: " . mysqli_error($connection));

}