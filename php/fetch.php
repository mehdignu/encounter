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
        $arr = array();


        $s .= "<div class='text-center'><h4>You have new requests !</h4></div><hr>";
        foreach ($result as $r) {
//            $s = "";
//            //$s .= "<a class=\"waves-effect waves-light btn-largemimi kaka\" href=\"create.php\">accept</a><a class=\"waves-effect waves-light btn-largemimi kaka\" href=\"create.php\"><i class=\"material-icons left\">add_circle</i>Create</a>";
//
//            $s .= "<button type='button' class='btn btn-primary ew' data-toggle='modal' id='".$x."' data-target='#exampleModal".$x."'> Show request </button>";
//
//
//            $arr[$x] = $s;
//            $x++;
//            $s = "";
//            $s .= "<div class='modal fade' id='exampleModal".$x."' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'> <div class='modal-dialog' role='document'> <div class='modal-content'> <div class='modal-header'> <h5 class='modal-title' id='exampleModalLabel'>request</h5> <button type='button' class='close' data-dismiss='modal' aria-label='Close'> <span aria-hidden='true'>&times;</span> </button> </div> <div class='modal-body'> Requester: <br> User: ".$r['requester']." want to join your encounter: ".$r['Title']." <br> Description: </div> <div class='modal-footer'> <button type='button' class='btn btn-success' data-dismiss='modal'>accept</button> <button type='button' class='btn btn-danger'>decline</button> </div> </div> </div> </div>";
//
//            $arr[$x] = $s;

            if($x !=0 && ($x % 2)!=0){
                $s .= '<hr>';
            }

           $s .= "<div id='elm".$x."' >
                   <div id='requestElm" . $x . "'><i class=\"fa fa-plus\"></i> User: ".$r['requester']." want to join your encounter: ".$r['Title']."</div>
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