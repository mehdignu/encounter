<?php
include("config.php");

//check the values from the database
if (isset($_POST['uname']) && isset($_POST['psw']) ){
    $firstname = $_POST['uname'];
    $password = $_POST['psw'];
    $result =mysqli_query($connection,"SELECT 1 FROM `users` WHERE `UserName` = '$firstname' AND `Password` = '$password'");
    if ($result && mysqli_num_rows($result) > 0)

    {
        $smsg = 'Username and Password Found';
    }
    else
    {
        $fmsg = 'Username and Password NOT Found';
    }
}
session_start();
if(isset($smsg)){  echo $smsg;
    $_SESSION['username'] = $firstname;
    header('Location: ../home/home.php');

}
if(isset($fmsg)){  echo $fmsg;}