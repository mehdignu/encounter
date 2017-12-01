<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.html");
}

include("../php/config.php");





if (isset($_POST['FirstName']) && isset($_POST['LastName']) && isset($_POST['gender']) && isset($_POST['about']) && isset($_POST['city'])){


   $user = $_SESSION['username'];

    $first = mysqli_real_escape_string($connection, strip_tags($_POST['FirstName']));
    $last = mysqli_real_escape_string($connection, strip_tags($_POST['LastName']));
    $gender = strip_tags($_POST['gender']);
    $about = mysqli_real_escape_string($connection, strip_tags($_POST['about']));
    $city= mysqli_real_escape_string($connection, strip_tags($_POST['city']));

    $query = "UPDATE `users` SET `FirstName`='$first', `LastName`='$last', `about`= '$about', `city`='$city' where `UserName`='$user'";


    $result = mysqli_query($connection, $query);

    if (!$result)
    {
        echo("Error description: " . mysqli_error($connection));
    }

    header('Location: home.php');

}

?>