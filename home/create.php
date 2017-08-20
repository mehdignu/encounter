<?php
$connection = mysqli_connect('localhost', 'mehdi', 'toor');
$connection->query('SET NAMES utf8');
if (!$connection){
    die("Database Connection Failed" . mysqli_error($connection));
}
$select_db = mysqli_select_db($connection, 'encounter');
if (!$select_db){
    die("Database Selection Failed" . mysqli_error($connection));
}

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


    $query = "INSERT INTO `scheduled` (Title, Description,Location,Date,Time,Max,Age,Age1) VALUES ('$title', '$description', '$location', '$date', '$time', '$max', '$age', '$age1')";
    $result = mysqli_query($connection, $query);
    if($result){
        $smsg = "User Created Successfully.";
    }else{
        $fmsg ="User Registration Failed";
    }
}

if(isset($smsg)){
   // echo $smsg;
    header('Location: home.php');
}
if(isset($fmsg)){  echo $fmsg;}


?>