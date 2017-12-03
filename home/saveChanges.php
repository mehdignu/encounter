<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
}

session_regenerate_id();
include("../php/config.php");








if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['location']) && isset($_POST['hour']) && isset($_POST['min']) && isset($_POST['max'])&& isset($_POST['date'])&& isset($_POST['age'])&& isset($_POST['age1']) ){
    $title = mysqli_real_escape_string($connection, strip_tags($_POST['title']));
    $description = mysqli_real_escape_string($connection, strip_tags($_POST['description']));
    $location = strip_tags($_POST['location']);

    $lat = $_POST['lat'];
    $lng = $_POST['lng'];

    $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lng.'&sensor=false';
    $json = @file_get_contents($url);
    $data = json_decode($json);
    $status = $data->status;
    $city = '';
    if($status=="OK") {
        //Get address from json data
        for ($j=0;$j<count($data->results[0]->address_components);$j++) {
            $cn=array($data->results[0]->address_components[$j]->types[0]);
            if(in_array("locality", $cn)) {
                $city= $data->results[0]->address_components[$j]->long_name;
            }
        }
    } else{
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
    $id = $_POST['id'];
    $query = "UPDATE `scheduled` SET `Title`='$title', Description='$description', Location='$location', lat='$lat', lng='$lng', city='$city',  `date`='$date', `Time`='$time', `Max`='$max', Age='$age', Age1='$age1' where `id`='$id'";


    $result = mysqli_query($connection, $query);

    if (!$result)
    {
        echo("Error description: " . mysqli_error($connection));
    }

    header('Location: home.php');

}

?>