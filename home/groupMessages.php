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
    <!-- Required meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script
            src="https://code.jquery.com/jquery-1.12.4.min.js"
            integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
            crossorigin="anonymous"></script>

    <script src="./frontend.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <style>

        .bg-company-red {
            background-color: #400000;
        }
        #content { padding:5px; background:#ddd; border-radius:5px;
            overflow-y: scroll;
            margin-bottom:10px;height:90%  }

    </style>

</head>

<body>

<!-- navuigation -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">Logo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="./home.php">Home <span class="sr-only">(current)</span></a>
            </li>

            <li class="nav-item dropdown" id="notifications" >
                <a class="nav-link" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell"></i> Notification <span class="badge" id="notification_count" value="<?php echo getNotiCount($_SESSION['username']) ?>"><?php echo getNotiCount($_SESSION['username']) ?></span></a>
                <div class="dropdown-menu" aria-labelledby="dropdown01" id="notificationsBody">
                    <a class="dropdown-item" href="#">user accepted your request</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">user accepted your request</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">user accepted your request</a>
                </div>
            </li>

            <li class="nav-item dropdown" id="encounters">
                <a class="nav-link" href="http://example.com" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-envelope"></i> Encounters    <span class="badge" id="messages_count" value="<?php echo getNotiEncCount($_SESSION['username']) ?>"><?php echo getNotiEncCount($_SESSION['username']) ?></span></a>
                <div class="dropdown-menu" aria-labelledby="dropdown02" id="messagessBody">
                    <a class="dropdown-item" href="#">encounter name here</a>
                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item" href="#">encounter name here</a>
                </div>
            </li>
        </ul>

        <ul class="navbar-nav">


            <li class="nav-item dropdown" >
                <a class="nav-link" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> Profile</a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="#">Settings</a>
                    <a class="dropdown-item" href="../php/logout.php">logout</a>
                </div>
            </li>
        </ul>


    </div>


</nav>
<input type="hidden" id="userName" value="<?php echo $_SESSION['username'] ?>">


<div class="container-fluid mx-auto" style="margin-top: 55px;">
    <div class="row">

        <div class="col-sm-2" style="background-color:#f1f1f1;position:fixed;height:100%;padding-top: 20px;">




        </div>


        <div class="col-sm-6 offset-sm-2" >

            <div class="card" style="position:fixed;height:92%;width:65%">
                <div class="card-header">
                    chat box
                </div>
                <div id="content"></div>

                <div class="card-block" style="position: absolute; bottom: 0;width: 100%" >
                    <div class="form-inline" >

                        <span id="status" class="col-sm-1.5"><?php echo $_SESSION['username'] ?></span>
                        <input type="text"  class="form-control offset-sm-1 col-sm-10" id="input" disabled="disabled" />
                    </div>
                </div>
            </div>

        </div>

        <div class="col-sm-2" style="background-color:#f1f1f1;right: 0; position: fixed;height:100%;padding-top: 20px;">

            <div class="card" style="background-color:#f1f1f1; height:5em;padding-top:10px;margin-top:30px;padding-left:5px;">
                <div class="card-block">
                    Online users
                </div>


            </div>
<br>

            <button type="button" class="btn btn-danger">leave</button>

        </div>

    </div>







    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="./messaging.js"></script>

    <script>
        window.onload = function() {

            document.getElementById("input").focus();
        };
        </script>
</body>
</html>