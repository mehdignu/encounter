<?php

session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
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

$result = mysqli_query($GLOBALS['connection'], "
         SELECT owner from scheduled WHERE id = '$EventId'
        ");

$row = mysqli_fetch_row($result);

$ownerId = $row[0];

$result = mysqli_query($GLOBALS['connection'], "
         SELECT UserName from users WHERE id = '$ownerId'
        ");


$row = mysqli_fetch_row($result);
$username = $row[0];



if(!empty($username)) {
    $exists = FALSE;
    if($_SESSION['username'] === $username)
        $exists = TRUE;

} else {
    session_regenerate_id(FALSE);
    session_unset();
}

if($exists === FALSE){
    session_regenerate_id(FALSE);
    session_unset();
}


if (!isset($_SESSION['username'])) {

    header("Location: ../index.html");

}

if($exists){


    //if the user delete his event give him one more allowed creation event
    $query = "UPDATE `users` SET allowedCre=allowedCre-1 WHERE `UserName`='$username'";
    $result = mysqli_query($connection, $query);

    $result = mysqli_query($GLOBALS['connection'], "
         DELETE from scheduled WHERE id = '$EventId'
        ");
    header("Location: home.php");

}




?>

