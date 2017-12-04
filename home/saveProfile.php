<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.html");
    return false;
}

session_regenerate_id();
include("../php/config.php");


if (isset($_POST['FirstName']) && isset($_POST['LastName']) && isset($_POST['gender']) && isset($_POST['about']) && isset($_POST['city'])) {


    $user = $_SESSION['username'];

    $path = "/Applications/XAMPP/xamppfiles/htdocs/encounter/user_uploads/";

    $query = "select ImageName from users where users.UserName = '$user'";
    $result = mysqli_query($connection, $query);

    $row = mysqli_fetch_row($result);

    $woo = true;

    $d=false;


    if ($_FILES["files"]["tmp_name"]) {

        if ($row[0] == 'Default.jpeg') {
            $d = true;
            $cc = getToken(10);

        } else {

            unlink($path.$row[0]);
            $cc = $row[0];

        }


        $destination_path = getcwd() . DIRECTORY_SEPARATOR;
        $target_file = $destination_path . basename($_FILES["files"]["name"]);

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


        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            header("Location: ../index.html");
            return;
            // if everything is ok, try to upload file
        } else {

            //change filename to UserName
            $temp = explode(".", $_FILES["files"]["name"]);
            $extension = end($temp);
            $path = "/Applications/XAMPP/xamppfiles/htdocs/encounter/user_uploads/";

            $filename = basename($_FILES["files"]["name"]);

            if($d){
                $filename = $cc . '.' . $extension;

            } else {
                $filename = $cc;
            }



            if (move_uploaded_file($_FILES["files"]["tmp_name"], $path . $filename)) {
                echo "The file " . basename($_FILES["files"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
                return false;
            }

        }
    } else {
        $woo = false;
    }

    //start
    $first = mysqli_real_escape_string($connection, strip_tags($_POST['FirstName']));



    $last = mysqli_real_escape_string($connection, strip_tags($_POST['LastName']));



    $city = mysqli_real_escape_string($connection, strip_tags($_POST['city']));


    $headers = @get_headers('https://www.weather-forecast.com/locations/' . $city . '/forecasts/latest');

    if ($headers[0] == "HTTP/1.1 404 Not Found") {
        header("Location: ../index.html");
        return false;

    }


    $gender = $_POST['gender'];

    $in = !in_array($gender, array('1', '2'), true);

    if (empty($gender) || $in) {
        return false;
    }



    $about = mysqli_real_escape_string($connection,strip_tags($_POST['about']));
    if(strlen($about) > 150 ) {

        return;
    }



    if ($woo == false) {
        $query = "UPDATE `users` SET `FirstName`='$first', `LastName`='$last', `about`= '$about', `city`='$city' where `UserName`='$user'";


        $result = mysqli_query($connection, $query);

        if (!$result) {
            echo("Error description: " . mysqli_error($connection));
        }
    } else {
        $query = "UPDATE `users` SET `ImageName`='$filename', `FirstName`='$first', `LastName`='$last', `about`= '$about', `city`='$city' where `UserName`='$user'";


        $result = mysqli_query($connection, $query);
        if (!$result) {
            echo("Error description: " . mysqli_error($connection));
        }
    }

    header("Location: ./home.php");

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