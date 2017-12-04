<?php


include("config.php");


// If the values are posted, insert them into the database.
if (isset($_POST['fName']) && isset($_POST['city']) && isset($_POST['lName']) && (isset($_POST['male']) || isset($_POST['female']) && isset($_POST['email']) && isset($_POST['psw']))) {


    $email = mysqli_real_escape_string($connection, strip_tags($_POST['email']));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../index.html");
        return;
    }


    //get notifications count
    $query = "select id from users where users.Email = '$email'";
    $result = mysqli_query($connection, $query);

    $row = mysqli_fetch_row($result);
    if (count($row[0]) != 0) {
        header("Location: ../index.html");
        return;
    }



    $cc = getToken(10);
    if ($_FILES["files"]["tmp_name"]) {


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
            $filename = $cc . '.' . $extension;


            if (move_uploaded_file($_FILES["files"]["tmp_name"], $path . $filename)) {
                echo "The file " . basename($_FILES["files"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
                return false;
            }
        }
    } else {
        $cc = 'Default.jpeg';
    }

    //start
    $firstname = mysqli_real_escape_string($connection, strip_tags($_POST['fName']));
    if (!preg_match("/^[a-zA-Z ]*$/", $firstname)) {
        header("Location: ../index.html");
        return false;

    }


    $lastName = mysqli_real_escape_string($connection, strip_tags($_POST['lName']));
    if (!preg_match("/^[a-zA-Z ]*$/", $lastName)) {
        header("Location: ../index.html");
        return false;

    }


    $city = mysqli_real_escape_string($connection, strip_tags($_POST['city']));
    if (!preg_match("/^[a-zA-Z ]*$/", $city)) {
        header("Location: ../index.html");
        return false;

    }

    $headers = @get_headers('https://www.weather-forecast.com/locations/' . $city . '/forecasts/latest');

    if ($headers[0] == "HTTP/1.1 404 Not Found") {
        header("Location: ../index.html");
        return false;

    }


    $gender = isset($_POST['male']) ? $_POST['male'] : $_POST['female'];

    $in = !in_array($gender, array('1', '2'), true);

    if (empty($gender) || $in) {
        header("Location: ../index.html");
        return false;
    }



    $password = mysqli_real_escape_string($connection, strip_tags($_POST['psw']));
    $p = password_hash($password, PASSWORD_DEFAULT);

    $uN = getToken(10);


    $query = "INSERT INTO `users` (UserName, ImageName,FirstName, LastName, gender,city ,Email, Password) VALUES ('$cc', '$filename','$firstname', '$lastName', '$gender', '$city','$email', '$p')";
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