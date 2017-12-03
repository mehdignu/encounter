<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
    return false;
}

session_regenerate_id();
include("../php/config.php");






if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['lat']) && isset($_POST['lng']) && isset($_POST['location']) && isset($_POST['hour']) && isset($_POST['min']) && isset($_POST['max']) && isset($_POST['date']) && isset($_POST['tag']) && isset($_POST['locDescription'])) {
    $bool = true;


    $title = mysqli_real_escape_string($connection,strip_tags($_POST['title']));
    if($title=='' || (strlen($title) > 26 )) {
        $bool = false;
    }


    $description = mysqli_real_escape_string($connection,strip_tags($_POST['description']));
    if($description==''|| (strlen($description) > 120 )) {
        $bool = false;

    }


    $location = mysqli_real_escape_string($connection,strip_tags($_POST['location']));
    $tag = strip_tags($_POST['tag']);
    $in = !in_array($tag, array('social','Date', 'sport', 'hobby'), true);

    if($in){
        header("Location: ../index.html");
        $bool = false;
        return false;
    }


    $locdesc = mysqli_real_escape_string($connection,strip_tags($_POST['locDescription']));
    if($locdesc==''|| (strlen($locdesc) > 120 )) {
        $bool = false;
    }

    $lat = $_POST['lat'];
    $lng = $_POST['lng'];


    $time = $_POST['hour'] . ':' . $_POST['min'];
    $max = $_POST['max'];
    $date = new DateTime($_POST['date']);
    $date = $date->format('Y-m-d');


    if (strtotime($date) < time()) {
        $bool = false;
    }



    if($bool==false){
        header("Location: ../index.html");
        return;
    }


    //add the eevent to the scheduled table
    $user = $_SESSION['username'];
    $id = $_POST['id'];
    $query = "UPDATE `scheduled` SET `Title`='$title', Description='$description', Location='$location', locDescription='$locdesc', lat='$lat', lng='$lng', city=(SELECT `city` FROM `users` WHERE `UserName` = '$user'),Tag='$tag', `date`='$date', `Time`='$time', `Max`='$max' where `id`='$id'";


    $result = mysqli_query($connection, $query);

    if (!$result)
    {
        echo("Error description: " . mysqli_error($connection));
    }

    header('Location: home.php');

}

?>