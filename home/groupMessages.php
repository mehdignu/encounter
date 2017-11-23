<?php

session_start();

include_once '../php/show.php';


/*
 * all dis to checkout if the user is allowed to visit the chat page (accepted to the encounter event)
 */


$EventId = $_GET['id'];

    $result = mysqli_query($GLOBALS['connection'], "
         SELECT userName from participations p
            inner join users u on u.id = p.MemberID
         WHERE EventID = '$EventId'
        ");


$rows = array();
while ($row = $result->fetch_array()) {
    $rows[] = $row;
}

if(!empty($rows)) {
    $exists = FALSE;
    foreach ($result as $r) {
        if($_SESSION['username'] === $r['userName']){
            $exists = TRUE;
        }
    }
} else {
    session_regenerate_id(FALSE);
    session_unset();
}

if($exists === FALSE){
    session_regenerate_id(FALSE);
    session_unset();
}


if (!isset($_SESSION['username'])) {

    header("Location: ../index.html");

}



?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>WebSockets - Simple chat</title>
    <style>
        * { font-family:tahoma; font-size:12px; padding:0px;margin:0px;}
        p { line-height:18px; }
        div { width:500px; margin-left:auto; margin-right:auto;}
        #content { padding:5px; background:#ddd; border-radius:5px;
            overflow-y: scroll; border:1px solid #CCC;
            margin-top:10px; height: 160px; }
        #input { border-radius:2px; border:1px solid #ccc;
            margin-top:10px; padding:5px; width:400px;
        }
        #status { width:88px;display:block;float:left;margin-top:15px; }
    </style>
</head>
<body>
<div id="content"></div>
<div>
    <span id="status"><?php echo $_SESSION['username'] ?></span>
    <input type="text" id="input" disabled="disabled" />
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="./messaging.js"></script>
</body>
</html>