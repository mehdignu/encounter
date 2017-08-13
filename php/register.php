<?php
$connection = mysqli_connect('localhost', 'mehdi', 'toor');
if (!$connection){
    die("Database Connection Failed" . mysqli_error($connection));
}
$select_db = mysqli_select_db($connection, 'encounter');
if (!$select_db){
    die("Database Selection Failed" . mysqli_error($connection));
}
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