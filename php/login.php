<?php


include("config.php");

//check the values from the database
if (isset($_POST['email']) && isset($_POST['psw']) ){


    $email = mysqli_real_escape_string($connection, strip_tags($_POST['email']));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $uploadOk = 0;
    }

    $password = mysqli_real_escape_string($connection,strip_tags($_POST['psw']));

    $stmt = $connection->prepare('SELECT Password FROM users WHERE Email= ?');
    $stmt->bind_param('s', $email);

    $stmt->execute();

    $result = $stmt->get_result();


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
            header("Location: ../index.html");

            return false;
        }
    } else {

        echo "user doesn't exits";
        header("Location: ../index.html");
        return false;
    }

         session_start();


    $stmt = $connection->prepare('SELECT UserName FROM users WHERE Email= ?');
    $stmt->bind_param('s', $email);

    $stmt->execute();

    $result = $stmt->get_result();

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

