<?php
/**
 * Created by PhpStorm.
 * User: mehdidridi
 * Date: 9/14/17
 * Time: 7:33 PM
 */

include("config.php");


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
        $s .= "<button id='msgsGrp'>".$r['Title']."</button>";
        $x++;
    }
} else {
    $s .= "<br/> <h4>No encounters yet</h4>";
}

echo $s;


