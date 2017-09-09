<?php
/**
 * Created by PhpStorm.
 * User: mehdidridi
 * Date: 9/9/17
 * Time: 7:38 PM
 */

include("../php/config.php");
$data = json_decode($_POST['request']);
$eventID = $data->eventid;

//delete request
$query = "delete from requests where requests.requestID = '$eventID'";

$result = mysqli_query($connection, $query);

if (!$result)
{

    echo("Error description: " . mysqli_error($connection));

}