<?php
include("config.php");

//check the values from the database
if (isset($_POST['email']) && isset($_POST['psw']) ){


    $email = $_POST['email'];
    $password = $_POST['psw'];
    $result =mysqli_query($connection,"SELECT Password FROM users WHERE Email='$email'");

    if ($result && mysqli_num_rows($result) > 0){
        while($row = $result->fetch_array())
        {
            $rows[] = $row;
        }
        foreach($rows as $row)
        {
            $HashedPass = $row['Password'];
        }

        if(!password_verify($password, $HashedPass)){
            echo "invalid password";

            return false;
        }
    } else {

        echo "user doesn't exits";
        return false;
    }

         session_start();
    $result =mysqli_query($connection,"SELECT UserName FROM users WHERE Email='$email'");

    if ($result && mysqli_num_rows($result) > 0) {

        while ($row = $result->fetch_array()) {
            $rows[] = $row;
        }
        foreach ($rows as $row) {
            $userName = $row['UserName'];
        }

    }

        $_SESSION['username'] = $userName;
        header('Location: ../home/home.php');


}

