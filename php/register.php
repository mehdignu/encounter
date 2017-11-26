<?php
include("config.php");


// If the values are posted, insert them into the database.
if (isset($_POST['fName']) && isset($_POST['lName']) && (isset($_POST['male']) || isset($_POST['female']) && isset($_POST['email']) && isset($_POST['psw']))) {

    $destination_path = getcwd().DIRECTORY_SEPARATOR;
    $target_file = $destination_path . basename( $_FILES["files"]["name"]);

    $target_dir = "/Applications/XAMPP/xamppfiles/htdocs/encounter/user_uploads/";
    $target_file = $target_dir . basename($_FILES["files"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["files"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }


    }


    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["files"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }


    //start
    $firstname = $_POST['fName'];
    $lastName = $_POST['lName'];
    $gender = isset($_POST['male']) ? $_POST['male'] : $_POST['female'];
    $email = $_POST['email'];
    $password = $_POST['psw'];
    $p = password_hash($password, PASSWORD_DEFAULT);
    $cc = getToken(10);


    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {

        //change filename to UserName
        $temp = explode(".", $_FILES["files"]["name"]);
        $extension = end($temp);
        $path="/Applications/XAMPP/xamppfiles/htdocs/encounter/user_uploads/";

        $filename = basename($_FILES["files"]["name"]);
        $filename = $cc .'.' . $extension;

            if (move_uploaded_file($_FILES["files"]["tmp_name"], $path . $filename)) {
            echo "The file " . basename($_FILES["files"]["name"]) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    $query = "INSERT INTO `users` (UserName, FirstName, LastName, gender, Email, Password) VALUES ('$cc', '$firstname', '$lastName', '$gender', '$email', '$p')";
    $result = mysqli_query($connection, $query);


    //after successful login enter home page

    session_start();

    $_SESSION['username'] = $cc;
    header('Location: ../home/home.php');

}


/**
 * generate random unique numbers to serve as UserName
 * @param $min
 * @param $max
 * @return int
 */
function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) {
        return $min;
    } // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int)($log / 8) + 1; // length in bytes
    $bits = (int)$log + 1; // length in bits
    $filter = (int)(1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);
    return $min + $rnd;
}

function getToken($length)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet .= "0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max - 1)];
    }

    return $token;
}


?>