<?php

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.html");
}
include_once '../php/show.php';


/*
 * all dis to checkout if the user is allowed to visit the chat page (accepted to the encounter event)
 */


$EventId = $_GET['id'];



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
    $result = mysqli_query($GLOBALS['connection'], "
         DELETE from scheduled WHERE id = '$EventId'
        ");
    header("Location: home.php");

}




?>

