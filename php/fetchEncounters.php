<?php
/**
 * Created by PhpStorm.
 * User: mehdidridi
 * Date: 9/14/17
 * Time: 7:33 PM
 */
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
    return false;
}

session_regenerate_id();
include("config.php");


/*
 * get the encounters from the database
 */

$data = json_decode($_POST['request']);
$userName = $data->userName;

$result = mysqli_query($GLOBALS['connection'], "
        SELECT u.UserName as 'participant', d.Title, p.*
    FROM users u
            inner join participations p on p.MemberID = u.id
            inner join scheduled d on d.id = p.EventID
    WHERE u.UserName = '$userName'
        ");

$rows = array();
$x = 0;
$s = '';
while ($row = $result->fetch_array()) {
    $rows[] = $row;

}

if(!empty($rows)){
    $s .= "<div class='text-center' style='font-family: Lobster;'><h4>Encounters:</h4></div>";

    foreach ($result as $r) {
        $s .= "<a class='dropdown-item msgsGrp' id='".$r['EventID']."' href='#' style='font-family: Bitter, serif;'><i class=\"fa fa-calendar\" aria-hidden=\"true\"></i> ".$r['Title']."</a> <div class='dropdown-divider'></div>";
        $x++;
    }
} else {
    $s .= "<br><br><a class=\"dropdown-item disabled text-center \" style='font-family: Bitter, serif;'><i class=\"fa fa-calendar\" aria-hidden=\"true\"></i><h5>No encounters yet</h5></a><br><br>";
}

echo $s;


