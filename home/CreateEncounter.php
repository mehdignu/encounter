<?php
session_start();
include("../php/config.php");

if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['location']) && isset($_POST['hour']) && isset($_POST['min']) && isset($_POST['max'])&& isset($_POST['date'])&& isset($_POST['age'])&& isset($_POST['age1']) ){
    $title = strip_tags($_POST['title']);
    $description = strip_tags($_POST['description']);
    $location = strip_tags($_POST['location']);
    $time = $_POST['hour'] . ':' . $_POST['min'];
    $max = $_POST['max'];
    $date = new DateTime($_POST['date']);
    $date = $date->format('Y-m-d');
    $age = $_POST['age'];
    $age1 = $_POST['age1'];
    //add the eevent to the scheduled table
    $user = $_SESSION['username'];
    $query = "INSERT INTO `scheduled` (`Title`, `Description`,`Location`,`Date`,`Time`,`Max`,`Age`,`Age1`,`owner`) VALUES ('$title', '$description', '$location', '$date', '$time', '$max', '$age', '$age1',(SELECT `id` FROM `users` WHERE `UserName` = '$user'))";
    $result = mysqli_query($connection, $query);
    if (!$result)
    {
        echo("Error description: " . mysqli_error($connection));
    }
    $lastEventId = mysqli_insert_id($connection);
    $query = "INSERT INTO `participations` (EventID, MemberID) VALUES ('$lastEventId', (SELECT `id` FROM `users` WHERE `UserName` = '$user'))";
    $result = mysqli_query($connection, $query);
    if (!$result)
    {
        echo("Error description: " . mysqli_error($connection));
    }
    if($result){
        $smsg = "event Created Successfully.";


    }else{
        $fmsg ="event creation Failed";
    }
}

if(isset($smsg)){
    // echo $smsg;
    header('Location: home.php');
}
if(isset($fmsg)){  echo $fmsg;}
?>