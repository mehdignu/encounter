<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.html");
}
include("../php/config.php");

$ew = $_SESSION['username'];

//increment requester allowed requests
$query = "SELECT allowedCre FROM `users` WHERE `UserName`='$ew'";
$result = mysqli_query($connection, $query);

$row = mysqli_fetch_row($result);
$count = $row[0];

if ($count < 3) {


    if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['lat']) && isset($_POST['lng']) && isset($_POST['location']) && isset($_POST['hour']) && isset($_POST['min']) && isset($_POST['max']) && isset($_POST['date']) && isset($_POST['age']) && isset($_POST['age1'])) {

        //increment requester allowed encounter created
        $user = $_SESSION['username'];
        $query = "UPDATE `users` SET allowedCre=allowedCre+1 WHERE `UserName`='$user'";
        $result = mysqli_query($connection, $query);

        $title = strip_tags($_POST['title']);
        $description = strip_tags($_POST['description']);
        $location = strip_tags($_POST['location']);

        $lat = $_POST['lat'];
        $lng = $_POST['lng'];

        $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lng . '&sensor=false&key=AIzaSyChPl2F6dKpwBPDbIqayOJCS42wWjTVmHw';
        $json = @file_get_contents($url);
        $data = json_decode($json);
        $status = $data->status;
        $city = '';
        if ($status == "OK") {
            //Get address from json data
            for ($j = 0; $j < count($data->results[0]->address_components); $j++) {
                $cn = array($data->results[0]->address_components[$j]->types[0]);
                if (in_array("locality", $cn)) {
                    $city = $data->results[0]->address_components[$j]->long_name;
                }
            }
        } else {

            $city = 'Location Not Found';
        }

        $time = $_POST['hour'] . ':' . $_POST['min'];
        $max = $_POST['max'];
        $date = new DateTime($_POST['date']);
        $date = $date->format('Y-m-d');
        $age = $_POST['age'];
        $age1 = $_POST['age1'];
        //add the eevent to the scheduled table
        $user = $_SESSION['username'];
        $query = "INSERT INTO `scheduled` (`Title`, `Description`,`Location`,`lat`,`lng`,`city`,`Date`,`Time`,`Max`,`Age`,`Age1`,`owner`) VALUES ('$title', '$description', '$location', '$lat', '$lng', '$city', '$date', '$time', '$max', '$age', '$age1',(SELECT `id` FROM `users` WHERE `UserName` = '$user'))";
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