<?php

session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
}

session_regenerate_id();
include("../php/config.php");

include_once '../php/show.php';


$requesterId = $_GET['id'];

if(preg_match("/[a-z]/i", $requesterId) || preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $requesterId)){
    header("Location: ../index.html");
    return false;
}

$stmt = $connection->prepare('SELECT Count(*) FROM users WHERE id= ?');
$stmt->bind_param('s', $requesterId);

$stmt->execute();

$result = $stmt->get_result();

$row = mysqli_fetch_row($result);

$count = $row[0];

if($count!=1){

    header("Location: ../index.html");
    return false;
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


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <script src="./frontend.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Barlow+Condensed" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/new.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster|Bitter|Cabin|Arimo" rel="stylesheet">

    <link href="../css/profile.css" rel="stylesheet" type="text/css"/>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <style>

        .dateme {
            text-align: center;
        }

        .line {
            height: 1px;
            background: #E6E8EB;
        }

        #ew {
            position: relative;
            top: 20px;
            background: #fff;
            display: inline-block;
            padding: 0 20px;
        }

        body {
            background-color: #E2E5E8;

            color: #455565;
            text-align: center;
            margin: 0px;
            padding: 0px;
            width: 100%;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            /*          z-index: 1000;*/
            min-height: 300px;
            display: none;
            min-width: 400px;
            float: left;
            padding: .5rem 0;
            margin: .125rem 0 0;
            font-size: 1rem;
            color: #212529;
            text-align: left;
            list-style: none;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, .15);
            border-radius: .25rem;
        }

        .bg-company-red {
            background-color: #400000;
        }

        #content {
            padding: 5px;
            background: #ffffff;

            margin-bottom: 10px;
            height: 96%
        }

    </style>

</head>

<body>

<!-- navuigation -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top" style="font-family: Arimo, sans-serif">



    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto" style="margin-left: 14%;">


            <li class="nav-item dropdown" id="notifications">
                <a class="nav-link" id="dropdown01" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell"></i> Notification <span
                            class="badge" id="notification_count"
                            value="<?php echo getNotiCount($_SESSION['username']) ?>"><?php echo getNotiCount($_SESSION['username']) ?></span></a>
                <div class="dropdown-menu" aria-labelledby="dropdown01" id="notificationsBody">


                </div>
            </li>

            <li class="nav-item dropdown" id="encounters">
                <a class="nav-link" id="dropdown02" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false"><i class="fa fa-envelope"></i> Encounters <span
                            class="badge" id="messages_count"
                            value="<?php echo getNotiEncCount($_SESSION['username']) ?>"><?php echo getNotiEncCount($_SESSION['username']) ?></span></a>
                <div class="dropdown-menu" aria-labelledby="dropdown02" id="messagessBody">


                </div>
            </li>

        </ul>


        <a class="navbar-brand nav-justified" href="./home.php"
           style="font-family: Lobster;  text-align: center;margin-right: 39%">encounter</a>


        <ul class="navbar-nav">


            <li class="nav-item dropdown" style="font-family: Arimo">
                <a class="nav-link" id="dropdown01" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> Profile</a>
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

        <div class="col-sm-2" style="background-color:#E2E5E8;position:fixed;height:100%;padding-top: 20px;">


            <br>


        </div>


        <div class="col-sm-6 offset-sm-2">

            <div class="card"
                 style="position:fixed;height:92%;width:65%;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">

                <div id="content">


                    <div id="pic">
                        <img src="../user_uploads/2qBIIeRE5B.jpeg"/>

                    </div>

                    <div id="wrapper"><br>

                        <?php

                        $result = fillProfile($requesterId);


                        if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {


                        ?>


                        <h1 class="desktop"><?php echo $row['FirstName'] .' '. $row['LastName'] ?></h1>



                            <h5>About me:</h5>
                        <p><?php echo $row['about'] ?></p>

                            <h5 id="element2">City: </h5><p id="element1"> <?php echo $row['city'] ?></p><br>


                            <h5 id="element2">Gender: </h5><p id="element1"> <?php echo $row['gender'] ? 'Male' : 'Female' ?></p>


                        <?php }}
                        ?>

                    </div>

                </div>

                <div class="card-block" style="position: absolute; bottom: 0;width: 100%;">
                    <div class="form-inline">

                    </div>
                </div>
            </div>

        </div>

        <div class="col-sm-2" style="background-color:#E2E5E8;right: 0; position: fixed;height:100%;padding-top: 20px;">


        </div>
        <br>


    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
            integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
            integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
            crossorigin="anonymous"></script>

    <!-- Compiled and minified JavaScript
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
-->
    <script>


        $(document).ready(function () {

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

            $('.dropdown-button').dropdown({
                    inDuration: 300,
                    outDuration: 225,
                    constrainWidth: false, // Does not change width of dropdown to that of the activator
                    hover: true, // Activate on hover
                    gutter: 0, // Spacing from edge
                    belowOrigin: false, // Displays dropdown below the button
                    alignment: 'left', // Displays dropdown with edge aligned to the left of button
                    stopPropagation: false // Stops event propagation
                }
            );


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


            //Popup on click
            /*  $("#messagesContainer").click(function () {
                  return false;
              });*/
        });

        //getting the number of elemnts inside the page as global variable
        if (document.querySelectorAll('.eventbtn').length > 0) {
            nr = document.querySelectorAll('.eventbtn').length;
        } else {
            nr = 0;
        }

        var i = 0;

        //make the id's name different
        var buttons = document.querySelectorAll('.eventbtn');
        for (i = 0; i < buttons.length; i++) {
            buttons[i].id = buttons[i].id + i;
        }
        buttons = document.querySelectorAll('.event-details');
        for (i = 0; i < buttons.length; i++) {
            buttons[i].id = buttons[i].id + i;
        }

        //make the id's name different for ask button
        buttons = document.querySelectorAll('.ask');
        for (i = 0; i < buttons.length; i++) {
            buttons[i].id = buttons[i].id + i;
        }

        //make the id's name different for ask button
        buttons = document.querySelectorAll('.own');
        for (i = 0; i < buttons.length; i++) {
            buttons[i].id = buttons[i].id + i;
        }

        //make the id's name different for event id's
        buttons = document.querySelectorAll('.evn');
        for (i = 0; i < buttons.length; i++) {
            buttons[i].id = buttons[i].id + i;
        }
        //make the id's name different for button texts id's
        buttons = document.querySelectorAll('#reqText');
        for (i = 0; i < buttons.length; i++) {
            buttons[i].id = buttons[i].id + i;
        }
        //make the id's name different for button texts id's
        buttons = document.querySelectorAll('#iconText');
        for (i = 0; i < buttons.length; i++) {
            buttons[i].id = buttons[i].id + i;
        }

        //make the id's name different for button texts id's
        buttons = document.querySelectorAll('#iconButt');
        for (i = 0; i < buttons.length; i++) {
            buttons[i].id = buttons[i].id + i;
        }

        //make the id's name different for button texts id's
        buttons = document.querySelectorAll('#evn');
        for (i = 0; i < buttons.length; i++) {
            buttons[i].id = buttons[i].id + i;
        }

        function HideIt() {
            document.getElementById("dismiss").style.display = 'none';
        }

        $("#dismiss").click(function () {
            $("#dismissMe").fadeOut(600);


            var userName = document.getElementById("userName").value;
            var data = {"type": 'checked', "userName": userName};

            $.ajax({
                type: "POST",
                url: "../php/checkedDismiss.php",
                data: {'checked': JSON.stringify(data)},
                cache: false,

                success: function (html) {
                    //alert(html);
                }
            });

        });


    </script>


</body>
</html>


