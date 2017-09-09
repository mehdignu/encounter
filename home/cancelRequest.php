<?php
/**
 * Created by PhpStorm.
 * User: mehdidridi
 * Date: 9/9/17
 * Time: 5:34 PM
 */

include("../php/config.php");


$data = json_decode($_POST['request']);
$eventID = $data->eventid;
$owner = $data->owner;
$userName = $data->username;


//get username id
$query = "select id from users where users.UserName = '$userName'";
$result = mysqli_query($connection, $query);
$userNameID = mysqli_fetch_assoc($result);
$userNameID = $userNameID['id'];

//get owner id
$query = "select id from users where users.UserName = '$owner'";
$result = mysqli_query($connection, $query);
$ownerID = mysqli_fetch_assoc($result);
$ownerID = $ownerID['id'];

//delete request
$query = "delete from requests where requests.eventID = '$eventID' AND requests.owner='$ownerID' AND requests.requester='$userNameID'";
$result = mysqli_query($connection, $query);

if (!$result)
{

    echo("Error description: " . mysqli_error($connection));

}
