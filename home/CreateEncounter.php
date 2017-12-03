<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.html");
    return false;
}

session_regenerate_id();
include("../php/config.php");

$ew = $_SESSION['username'];

//increment requester allowed requests
$query = "SELECT allowedCre FROM `users` WHERE `UserName`='$ew'";
$result = mysqli_query($connection, $query);

$row = mysqli_fetch_row($result);
$count = $row[0];

if ($count < 3) {


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


        //increment requester allowed encounter created
        $user = $_SESSION['username'];
        $query = "UPDATE `users` SET allowedCre=allowedCre+1 WHERE `UserName`='$user'";
        $result = mysqli_query($connection, $query);

            //add the eevent to the scheduled table
        $user = $_SESSION['username'];
        $query = "INSERT INTO `scheduled` (`Title`, `Description`,`Location`,`locDescription`,`lat`,`lng`,`city`,`tag`,`Date`,`Time`,`Max`,`owner`) VALUES ('$title', '$description', '$location','$locdesc' , '$lat', '$lng', (SELECT `city` FROM `users` WHERE `UserName` = '$user'),'$tag' ,'$date', '$time', '$max',(SELECT `id` FROM `users` WHERE `UserName` = '$user'))";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            echo("Error description: " . mysqli_error($connection));
        }


        $lastEventId = mysqli_insert_id($connection);
        $query = "INSERT INTO `participations` (EventID, MemberID) VALUES ('$lastEventId', (SELECT `id` FROM `users` WHERE `UserName` = '$user'))";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            echo("Error description: " . mysqli_error($connection));
        }
        if ($result) {
            $smsg = "event Created Successfully.";


        } else {
            $fmsg = "event creation Failed";
        }
    }

    if (isset($smsg)) {
        // echo $smsg;
        header('Location: home.php');
    }
    if (isset($fmsg)) {
        echo $fmsg;
    }

} else {
    header('Location: home.php');
}
?>