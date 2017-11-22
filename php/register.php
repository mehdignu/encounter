<?php
include("config.php");

$msg;
// If the values are posted, insert them into the database.
if (isset($_POST['fName']) && isset($_POST['lName']) && (isset($_POST['male']) || isset($_POST['female']) && isset($_POST['email']) && isset($_POST['psw'])) ){
    $firstname = $_POST['fName'];
    $lastName = $_POST['lName'];
    $gender = isset($_POST['male']) ? $_POST['male'] : $_POST['female'];
    $email = $_POST['email'];
    $password = $_POST['psw'];
    $p = password_hash($password, PASSWORD_DEFAULT);
    $cc = getToken(10);


    $query = "INSERT INTO `users` (UserName, FirstName, LastName, gender, Email, Password) VALUES ('$cc', '$firstname', '$lastName', '$gender', '$email', '$p')";
    $result = mysqli_query($connection, $query);


    $p =

    exit;
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
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
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
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
    }

    return $token;
}



?>