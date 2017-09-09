<?php
include("config.php");

// If the values are posted, insert them into the database.
if (isset($_POST['uname']) && isset($_POST['psw']) ){
    $firstname = $_POST['uname'];
    $password = $_POST['psw'];

    $query = "INSERT INTO `users` (UserName, Password) VALUES ('$firstname', '$password')";
    $result = mysqli_query($connection, $query);
    if($result){
        $smsg = "User Created Successfully.";
    }else{
        $fmsg ="User Registration Failed";
    }
}
if(isset($smsg)){ echo $smsg;}
if(isset($fmsg)){  echo $fmsg;}
?>