<?php

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.html");
}
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

$u = $_SESSION['username'];

$result = mysqli_query($GLOBALS['connection'], "
         SELECT id from users WHERE UserName = '$u'
        ");

$row = mysqli_fetch_row($result);

$memberId = $row[0];


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

            <?php $enc = getEncounter($EventId);
            if ($enc) {
            while ($row = mysqli_fetch_assoc($enc)) {

            ?>

                <b>Encounter:</b> <br><span value="<?php echo $row['Title'] ?>"><?php echo $row['Title'] ?></span><br>
                <b>Description:</b><br>
                <span value="<?php echo $row['Description'] ?>"><?php echo $row['Description'] ?></span><br>


                <b>Address:</b><br>
                <span type="text" id="us3-address" name="location"  value="<?php echo $row['Location'] ?>" ><?php echo $row['Location'] ?></span><br>
                <input type="hidden" name="lat" id="lat" value="<?php echo $row['lat'] ?>">
                <input type="hidden" name="lng" id="lng" value="<?php echo $row['lng'] ?>">


                <div id="loc" style="width: 100%; height: 25em; margin-top: 15px;" ></div><br />



                <span><b>when ?</b></span><br>
                <span><?php echo date('jS F Y', strtotime($row['Date'])) .' at '. $row['Time'] ?></span><br>


                <?php

                if($row['owner'] == $memberId) {
                    ?>

                    <button type="button" class="btn btn-danger fixed-bottom" onclick="location.href = 'removeEncounter.php?id=<?php echo $EventId ?>'" style="margin-bottom: 29px;margin-left:9px;width:auto">delete Encounter</button>

                    <?php

                }else {
                    ?>

                    <button type="button" class="btn btn-secondary fixed-bottom"
                            onclick="location.href = 'leaveEncounter.php?id=<?php echo $EventId ?>&member=<?php echo $_SESSION['username'] ?>'"
                            style="margin-bottom: 29px;margin-left: 45px;width:200px">leave Encounter
                    </button>


                    <?php
                }
            }
            }
            ?>
            <br>


        </div>


        <div class="col-sm-6 offset-sm-2" >

            <div class="card" style="position:fixed;height:92%;width:65%">
                <div class="card-header">
                    chat box
                </div>
                <div id="content"></div>

                <div class="card-block" style="position: absolute; bottom: 0;width: 100%;" >
                    <div class="form-inline" >

                        <span id="status" class="col-sm-1" style="padding-left: 10px;position:absolute;"><?php echo getUserName($_SESSION['username']); ?></span>
                        <input type="text"  class="form-control offset-sm-2 col-sm-10" id="input" disabled="disabled" />
                    </div>
                </div>
            </div>

        </div>

        <div class="col-sm-2" style="background-color:#f1f1f1;right: 0; position: fixed;height:100%;padding-top: 20px;">


                    Encounter attenders
<br>
                    <?php
                    $rowww = getAttenders($EventId);


                    ?>


                        <ul class="list-group list-group-flush" style="background-color: #f1f1f1">


                    <?php

                    for ($i = 0; $i < count($rowww); $i++) {
                        $exists = glob("/Applications/XAMPP/xamppfiles/htdocs/encounter/user_uploads/" . $rowww[$i]['attenders'] . ".*");

                        if (count($exists)) {
                            ?>
                            <li class="list-group-item" style="background-color: #f1f1f1">
                            <img class='rounded-circle' height="60px" width="60px"
                                 src='../user_uploads/<?php echo $rowww[$i]["attenders"] ?>.jpeg'/>
                            </li>

                            <?php echo getUserName($rowww[$i]["attenders"]); ?>


                            <?php
                        }

                        ?>


                    <?php } ?>
                        </ul>
            </div>
<br>




    </div>







    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="./messaging.js"></script>
    <script type="text/javascript" src='http://maps.google.com/maps/api/js?libraries=places&key=AIzaSyBCVuS83u7FTWYsPCpKN8QoVJeIiSmmo1Y'></script>
    <script src="../js/locationpicker.jquery.js"></script>
    <script>
        window.onload = function() {

            //notifications Requests count
            var count = document.getElementById("notification_count").innerText;
            var d1 = document.getElementById("dropdown01");

            //alert(count);
            if (count == 0) {
                d1.classList.remove("active");
                $("#notification_count").hide();
            } else {
                d1.className += " active";
                $("#notification_count").show();
            }

            //notifications Encounters count
            var countNoti = document.getElementById("messages_count").innerText;
            var d2 = document.getElementById("dropdown02");
            if (countNoti == 0) {
                d2.classList.remove("active");

                $("#messages_count").hide();
            } else {
                d2.className += " active";

                $("#messages_count").show();

            }


            $("#notifications").click(function () {
                //$("#messagesContainer").hide();
                // $("#notificationContainer").fadeToggle(300);
                //$("#notification_count").fadeOut("slow");
                d1.classList.remove("active");
                $("#notification_count").fadeOut("slow");

                var userName = document.getElementById("userName").value;

                var data = {"type": 'NotifyRequestReset', "userName": userName};

                $.ajax({
                    type: "POST",
                    url: "NotifyRequestReset.php",
                    data: {'NotifyRequestReset': JSON.stringify(data)},
                    cache: false,

                    success: function (html) {
                        //alert(html);
                    }
                });

                //return false;
            });


            /* messages */

            $("#encounters").click(function () {
                //   $("#notificationContainer").hide();
                //  $("#messagesContainer").fadeToggle(300);
                // $("#messages_count").fadeOut("slow");
                d2.classList.remove("active");
                $("#messages_count").fadeOut("slow");

                var userName = document.getElementById("userName").value;
                var data = {"type": 'NotifyEncounterReset', "userName": userName};


                $.ajax({
                    type: "POST",
                    url: "NotifyEncounterReset.php",
                    data: {'NotifyEncounterReset': JSON.stringify(data)},
                    cache: false,

                    success: function (html) {
                        //alert(html);
                    }
                });


                //return false;
            });



            document.getElementById("input").focus();


            //google maps api to select location
            $('#loc').locationpicker({
                location: {
                    latitude: $('#lat').val(),
                    longitude: $('#lng').val()
                },
                radius: 300,
                inputBinding: {
                    locationNameInput: $('#us3-address')
                },
                enableAutocomplete: true,
                onchanged: function (currentLocation, radius, isMarkerDropped) {
                    $('#lat').val(currentLocation.latitude);
                    $('#lng').val(currentLocation.longitude);
                }
            });

        };
        </script>
</body>
</html>