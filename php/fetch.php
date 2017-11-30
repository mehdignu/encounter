<?php
/**
 * Created by PhpStorm.
 * User: mehdidridi
 * Date: 9/10/17
 * Time: 3:08 PM
 */

include("config.php");
include("show.php");


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
        $arr = array();


        $s .= "<div class='text-center'><h4>You have new requests !</h4></div><hr>";
        foreach ($result as $r) {

            if($x !=0 && ($x % 2)!=0){
                $s .= '<hr>';
            }
            $realName = getUserName($r['requester']);
            $id = getId($r['requester']);


           $s .= "<div id='elm".$x."' >
                   <div id='requestElm" . $x . "' style='text-align:center'> <a href='../home/profile.php?id=".$id."'> ".$realName." </a> want to join your encounter: ".$r['Title']."</div>
                   <div class=' text-center'>
                   <button id='".$x."' class='btn btn-success  acceptReq'>accept</button>
                   <button id='".$x."' class='btn btn-danger red denyReq'>deny</button>  
                   </div>
                   <input type='hidden' id='reqID".$x."' value='".$r['requestID']."'>
                   <input type='hidden' id='requester".$x."' value='".$r['requester']."'></div>";
            $x++;
        }
    } else {
      //  $s .= "<br/> <h4>No requests yet</h4>";


        $s .= "<br><br><a class=\"dropdown-item disabled text-center \"><h4>No requests yet</h4></a><br><br>";
    }

    echo $s;