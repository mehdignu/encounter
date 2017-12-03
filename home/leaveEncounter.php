<?php

session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
    return false;
}

session_regenerate_id();

include_once '../php/show.php';


/*
 * all dis to checkout if the user is allowed to visit the chat page (accepted to the encounter event)
 */



$EventId = $_GET['id'];

if(preg_match("/[a-z]/i", $EventId) || preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $EventId)){
    header("Location: ../index.html");
}


$userName = $_GET['member'];


if($_SESSION['username'] == $userName){
    $result = mysqli_query($GLOBALS['connection'], "
         SELECT id from users WHERE UserName = '$userName'
        ");

    $row = mysqli_fetch_row($result);

    $memberId = $row[0];


    $result = mysqli_query($GLOBALS['connection'], "
         DELETE  from participations WHERE EventID = '$EventId' AND MemberID='$memberId'
        ");


    //increment requester allowed requests
    $query = "UPDATE `users` SET allowedReq=allowedReq-1 WHERE `id`='$memberId'";
    $result = mysqli_query($connection, $query);

    //decrement the number of the participants
    $query = "UPDATE `scheduled` SET `particNum`=`particNum`-1 WHERE scheduled.id='$EventId'";
    $result = mysqli_query($connection, $query);


    //decrement encCount if not clicked on the dropdown
    $query = "select EncCount from users where users.UserName = '$userName'";
    $result = mysqli_query($GLOBALS['connection'], $query);

    $row = mysqli_fetch_row($result);

    $count = $row[0];

    if($count > 0){
        $query = "UPDATE `users` SET `EncCount`=`EncCount`-1 WHERE users.UserName='$userName'";
        $result = mysqli_query($connection, $query);
    }

    header("Location: home.php");



}




?>