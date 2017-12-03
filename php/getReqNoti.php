<?php


session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
}

session_regenerate_id();
include("config.php");


//dynamic Notifications count changing
$data = json_decode($_POST['request']);
$userName = $data->userName;

//get notifications count
$query = "select ReqCount from users where users.UserName = '$userName'";
$result = mysqli_query($connection, $query);
if (!$result)
{

    echo("Error description: " . mysqli_error($connection));

}
$s ='';
//fetch result
$row = mysqli_fetch_row($result);
echo $row[0];