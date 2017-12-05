<?php

session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
    return false;
}

session_regenerate_id();
include_once 'config.php';
include_once 'show.php';

/*
 * all dis to checkout if the user is allowed to visit the chat page (accepted to the encounter event)
 */


$userId = $_GET['id'];

if(preg_match("/[a-z]/i", $EventId) || preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $EventId)){
    header("Location: ../index.html");
    return false;
}

$result = mysqli_query($GLOBALS['connection'], "
         SELECT UserName from users WHERE id = '$userId'
        ");

$row = mysqli_fetch_row($result);

$iddd = $row[0];



if(!empty($iddd)) {
    $exists = FALSE;
    if($_SESSION['username'] === $iddd)
        $exists = TRUE;

} else {
    session_regenerate_id(FALSE);
    session_unset();
}

if($exists === FALSE){
    session_regenerate_id(FALSE);
    session_unset();
    header("Location: ../index.html");
}


if (!isset($_SESSION['username'])) {

    header("Location: ../index.html");
    return false;

}



if($exists){



    $result = mysqli_query($GLOBALS['connection'], "
         DELETE from users WHERE id = '$userId'
        ");
    header("Location: ../index.html");

}




?>

