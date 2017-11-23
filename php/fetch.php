<?php
/**
 * Created by PhpStorm.
 * User: mehdidridi
 * Date: 9/10/17
 * Time: 3:08 PM
 */

include("config.php");


/*
 * get the encounter requests from the database
 */

$data = json_decode($_POST['request']);
$userName = $data->userName;

    $result = mysqli_query($GLOBALS['connection'], "
        SELECT k.UserName as 'requester', d.Title, s.requestID
    FROM users u
        inner join requests s on u.id = s.owner
            inner join scheduled d on d.id = s.eventID
            inner join users k on k.id=s.requester
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
            $s .= "<div id='elm".$x."'>
                   <div id='requestElm" . $x . "'>User: ".$r['requester']." want to join your encounter: ".$r['Title']."</div>
                   <button id='".$x."' class='acceptReq'>accept</button>
                   <button id='".$x."' class='denyReq'>deny</button>  
                   <input type='hidden' id='reqID".$x."' value='".$r['requestID']."'>
                   <input type='hidden' id='requester".$x."' value='".$r['requester']."'></div>";
            $x++;
        }
    } else {
      //  $s .= "<br/> <h4>No requests yet</h4>";


        $s .= "<br><br><a class=\"dropdown-item disabled \">No requests yet</a><br><br>";
    }

    echo $s;