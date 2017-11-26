<?php
/**
 * Created by PhpStorm.
 * User: mehdidridi
 * Date: 9/14/17
 * Time: 7:33 PM
 */

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
    foreach ($result as $r) {
        $s .= "<a class='dropdown-item msgsGrp' id='".$r['EventID']."' href='#'>".$r['Title']."</a> <div class='dropdown-divider'></div>";
        $x++;
    }
} else {
    $s .= "<br><br><a class=\"dropdown-item disabled \">No encounters yet</a><br><br>";
}

echo $s;


