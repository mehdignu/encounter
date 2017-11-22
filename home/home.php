<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.html");
}

include_once '../php/show.php';


?>
<!--
<!DOCTYPE html>
<html>


<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script
            src="https://code.jquery.com/jquery-1.12.4.min.js"
            integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
            crossorigin="anonymous"></script>
    <script src="./frontend.js"></script>

    <script src="https://unpkg.com/vue"></script>


    <link rel="stylesheet" type="text/css" href="../css/home.css">
    <meta name="description"
          content="Mehdi Dridi. I'm a software engineer obsessed with code quality, software architecture, and building experiences.">
    <script src="../js/home.js"></script>

    <title>home</title>
</head>

-->

<!--

<body>

<div class="topnav">
    <div class="boo">
        <a class="active" href="#home">Home</a>

        -->


        <!-- encounters -->

<!--
        <a id="messages_li">
            <span id="messages_count" type="hidden" value="<?php echo getNotiEncCount($_SESSION['username']) ?>"><?php echo getNotiEncCount($_SESSION['username']) ?></span>

            <a href="#" id="messagesLink">encounters</a>

            <div id="messagesContainer">
                <div id="messagesTitle">encounters</div>

                <div id="messagessBody" class="messagess">


                </div>


                <div id="messagesFooter"><a href="#">See All</a></div>
            </div>
        </a>
-->

        <!-- encounter requests -->
<!--
        <a id="notification_li">

            <span id="notification_count" type="hidden" value="<?php echo getNotiCount($_SESSION['username']) ?>"><?php echo getNotiCount($_SESSION['username']) ?></span>

            <a href="#" id="notificationLink">Notifications</a>

            <div id="notificationContainer">
                <div id="notificationTitle">Notifications</div>

                <div id="notificationsBody" class="notifications">


                </div>


                <div id="notificationFooter"><a href="#">See All</a></div>
            </div>
        </a>


        <a href="#about">username : <?php echo $_SESSION['username'] ?></a>
    </div>
    <div class="dropdown">
        <a onclick="dropMenu(this.id,nr)" id="navSec" class="dropbtn">Settings</a>
        <div id="myDropdown" class="dropdown-content">
            <a href="#home">Profile </a>
            <a href="../php/logout.php">Logout</a>
        </div>
    </div>

</div>

<input type="hidden" id="userName" value="<?php echo $_SESSION['username'] ?>">

<div style="padding-left:16px">
    <h2>Top Navigation Example</h2>
    <p>Some content..</p>
    <a class="button button5" href="create.html">Create an encounter</a>
    <a class="button button5">Find an encounter</a>
    <br/>
    <!--    <a class="button button6">some event</a> -->
<!--
    <?php

    $result = eventsArray();
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="eventwrapper">
            <button onclick="dropMenu(this.id,nr)" id="" class="eventbtn"><?php printf("%s\n",
                    $row["Title"]); ?></button>
            <div id="myDropdown" class="event-details">

                <div class="wrp">

                    <div class="details">
                        <h4>Description:</h4>
                        is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                        industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of
                        type and scrambled it to make a type specimen book.
                    </div>

                    <h4>Date/Time: <span class="timing">10/12/2017 - 18:15pm</span></h4>

                    <h4>Attenders <span class="timing">1 of 5</span></h4>

                    <?php $owner = getOwner($row['owner']) ?>
                    <?php $isRequested = isRequested($_SESSION['username'], $row['id']) ?>
                    <?php $isAccepted = isAccepted($_SESSION['username'], $row['id']) ?>

                    <div class="joining">

                    -->
                        <!-- compare owner of the event with the session user if they are the same notify the user that someone want's to join the event -->

                     <!--   <input type="hidden" id="evn" value="<?php echo $row['id'] ?>">

                        <a class="button button5 ask" id=""> <?php if($isAccepted == TRUE) { ?>

                                <span id="reqText" ><?php echo 'Enter encounter'; ?></span>

                            <?php } else { ?>
                            <?php if($isRequested == TRUE){ ?>


                                <span id="reqText"><?php echo 'requested'; ?></span>

                            <?php } else { ?>

                                <span id="reqText"><?php echo 'Ask To Join'; ?></span>

                            <?php } }?></a>

                        <input type="hidden" class="own" id="owner" value="<?php echo $owner ?>">
                        <input type="hidden" class="evn" id="eventid" value="<?php echo $row['id'] ?>">

                    </div>

                </div>

            </div>
        </div>


        <?php

    }

    ?>


</div>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Encounter | Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
        /* Remove the navbar's default margin-bottom and rounded borders */
        .navbar {
            margin-bottom: 0;
            border-radius: 0;
        }

        /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
        .row.content {height: 450px}

        /* Set gray background color and 100% height */
        .sidenav {
            padding-top: 20px;
            background-color: #f1f1f1;
            height: 100%;
        }

        /* Set black background color, white text and some padding */
        footer {
            background-color: #555;
            color: white;
            padding: 15px;
        }

        /* On small screens, set height to 'auto' for sidenav and grid */
        @media screen and (max-width: 767px) {
            .sidenav {
                height: auto;
                padding: 15px;
            }
            .row.content {height:auto;}
        }



        /*
        .content{
            display: table;
            width: 100%;
        }

        .col {
            display: table-cell;
        }

        @media only screen and (max-width: 600px) {
            .col {
                display: block;
                width: 100%;
            }
        }
*/

    .sidenav{

    }
    </style>
</head>
<body>

<nav class="navbar navbar-inverse" data-spy="affix" id="myNav" >
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Logo</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>


                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-envelope"></span> Encounters <span class="badge">42</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Start Learning</a></li>
                        <li><a href="#">View All Courses</a></li>
                        <li><a href="#">Chat with a CodeGuide</a></li>
                    </ul>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-bell"></span> Notifications <span class="badge">42</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Start Learning</a></li>
                        <li><a href="#">View All Courses</a></li>
                        <li><a href="#">Chat with a CodeGuide</a></li>
                    </ul>

            </ul>


            <ul class="nav navbar-nav navbar-right ">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-user"></span> Profile</a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Start Learning</a></li>
                        <li><a href="#">View All Courses</a></li>
                        <li><a href="#">Chat with a CodeGuide</a></li>
                    </ul>


            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid text-center">

    <div class="row content ">

        <div class="col-sm-2 sidenav">
            <p><a href="#">Link</a></p>
            <p><a href="#">Link</a></p>
            <p><a href="#">Link</a></p>
        </div>

        <div class="col-sm-8 text-left">

            <div class="panel panel-default">
                <div class="panel-heading">Panel Heading</div>
                <div class="panel-body">Panel Content</div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Panel Heading</div>
                <div class="panel-body">Panel Content</div>
            </div><div class="panel panel-default">
                <div class="panel-heading">Panel Heading</div>
                <div class="panel-body">Panel Content</div>
            </div><div class="panel panel-default">
                <div class="panel-heading">Panel Heading</div>
                <div class="panel-body">Panel Content</div>
            </div><div class="panel panel-default">
                <div class="panel-heading">Panel Heading</div>
                <div class="panel-body">Panel Content</div>
            </div><div class="panel panel-default">
                <div class="panel-heading">Panel Heading</div>
                <div class="panel-body">Panel Content</div>
            </div><div class="panel panel-default">
                <div class="panel-heading">Panel Heading</div>
                <div class="panel-body">Panel Content</div>
            </div><div class="panel panel-default">
                <div class="panel-heading">Panel Heading</div>
                <div class="panel-body">Panel Content</div>
            </div><div class="panel panel-default">
                <div class="panel-heading">Panel Heading</div>
                <div class="panel-body">Panel Content</div>
            </div><div class="panel panel-default">
                <div class="panel-heading">Panel Heading</div>
                <div class="panel-body">Panel Content</div>
            </div><div class="panel panel-default">
                <div class="panel-heading">Panel Heading</div>
                <div class="panel-body">Panel Content</div>
            </div><div class="panel panel-default">
                <div class="panel-heading">Panel Heading</div>
                <div class="panel-body">Panel Content</div>
            </div>
        </div>


        <div class="col-sm-2 sidenav">
            <div class="well">
                <p>ADS</p>
            </div>
            <div class="well">
                <p>ADS</p>
            </div>
        </div>
    </div>
</div>
<!--
<footer class="container-fluid text-center">
    <p>Footer Text</p>
</footer>
-->

<!doctype html>
<html lang="en">
<head>
    <title>encounter</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
<style>


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
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item dropdown" >
                <a class="nav-link" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell"></i> Notification <span class="badge">19</span></a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="#">user accepted your request</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">user accepted your request</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">user accepted your request</a>
                </div>
            </li>

            <li class="nav-item dropdown" >
                <a class="nav-link" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-envelope"></i> Encounters    <span class="badge">5</span></a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
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
                    <a class="dropdown-item" href="#">Sign out</a>
                </div>
            </li>
        </ul>


    </div>


</nav>


<div class="container-fluid" style="margin-top: 55px;">
    <div class="row">

        <div class="col-sm-2" style="background-color:#f1f1f1;position:fixed;height:100%;padding-top: 20px;">

            <h4>Your upcoming encounters :</h4>


            <p><a href="#">Link</a></p>
            <p><a href="#">Link</a></p>
            <p><a href="#">Link</a></p>

        </div>
        <div class="col-sm-8 col-sm-8 offset-sm-2 col-md-8" >
            <br>
<h4>Friday 10 2017</h4>
            <hr>
            <div class="card mx-auto " style="width: 35rem;">
                <!--
                <img class="card-img-top" src="..." alt="Card image cap">
                -->
                <div class="card-block">

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <h4 class="card-title">  Event title</h4>
                        </li>
                        <li class="list-group-item">
                            <h4>Description</h4>
                            <p class="card-text">Description of the eventDescription of the eventDescription of the eventDescription of the eventvDescription of the eventDescription of the eventDescription of the event</p>
                        </li>
                        <li class="list-group-item">
                            <p>3 of 5 people are attending the event</p>
                            <img class='rounded-circle' height="60px" width="60px" src='http://www.math.uni-frankfurt.de/~person/_4170854.jpg' />
                            <img class='rounded-circle' height="60px" width="60px" src='http://via.placeholder.com/350x150' />
                            <img class='rounded-circle' height="60px" width="60px" src='http://via.placeholder.com/350x150' />


                        </li>

                    </ul>



                </div>

                <br />
                <ul class="list-group list-group-flush">
                    <li  class="list-group-item">Date/Time: 10/12/2017 - 18:15pm</li>

                </ul>
                <div class="card-block text-center">
                    <button type="button" class="btn btn-primary btn-lg btn-block">Ask to Join</button>

                </div>


            </div>



        </div>
        <div class="col-sm-2" style="background-color:#f1f1f1;right: 0; position: fixed;height:100%;padding-top: 20px;">

            <div class="well">
                <p>ADS</p>
            </div>
            <div class="well">
                <p>ADS</p>
            </div>

        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>

<script>
    $(document).ready(function () {


      //  $("#notification_count").hide();
       // $("#messages_count").hide();

        //notifications Requests count
        var count = document.getElementById("notification_count").innerText;
        //alert(count);
        if(count == 0){
            $("#notification_count").hide();
        } else {
            $("#notification_count").show();

        }

        //notifications Encounters count
        var countNoti = document.getElementById("messages_count").innerText;
        //alert(count);
        if(countNoti == 0){
            $("#messages_count").hide();
        } else {
            $("#messages_count").show();

        }



        $("#notificationLink").click(function () {
            $("#messagesContainer").hide();
            $("#notificationContainer").fadeToggle(300);
            $("#notification_count").fadeOut("slow");

            var userName = document.getElementById("userName").value;
            var dataString = {"type": 'NotifyRequestReset', "userName": userName};

            $.ajax({
                type: "POST",
                url: "NotifyRequestReset.php",
                data: {'NotifyRequestReset': JSON.stringify(dataString)},
                cache: false,

                success: function (html) {
                    //alert(html);
                }
            });

            return false;
        });

        //Document Click hiding the popup
        $(document).click(function () {
            $("#notificationContainer").hide();
            $("#messagesContainer").hide();
        });

        //Popup on click
        $("#notificationContainer").click(function () {
            return false;
        });

        /* messages */

        $("#messagesLink").click(function () {
            $("#notificationContainer").hide();
            $("#messagesContainer").fadeToggle(300);
            $("#messages_count").fadeOut("slow");


            var userName = document.getElementById("userName").value;
            var dataString = {"type": 'NotifyEncounterReset', "userName": userName};

            $.ajax({
                type: "POST",
                url: "NotifyEncounterReset.php",
                data: {'NotifyEncounterReset': JSON.stringify(dataString)},
                cache: false,

                success: function (html) {
                    //alert(html);
                }
            });


            return false;
        });



        //Popup on click
        $("#messagesContainer").click(function () {
            return false;
        });
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
    for ( i = 0; i < buttons.length; i++) {
        buttons[i].id = buttons[i].id + i;
    }
     buttons = document.querySelectorAll('.event-details');
    for ( i = 0; i < buttons.length; i++) {
        buttons[i].id = buttons[i].id + i;
    }

    //make the id's name different for ask button
     buttons = document.querySelectorAll('.ask');
    for ( i = 0; i < buttons.length; i++) {
        buttons[i].id = buttons[i].id + i;
    }

    //make the id's name different for ask button
     buttons = document.querySelectorAll('.own');
    for ( i = 0; i < buttons.length; i++) {
        buttons[i].id = buttons[i].id + i;
    }

    //make the id's name different for event id's
     buttons = document.querySelectorAll('.evn');
    for ( i = 0; i < buttons.length; i++) {
        buttons[i].id = buttons[i].id + i;
    }
    //make the id's name different for button texts id's
     buttons = document.querySelectorAll('#reqText');
    for ( i = 0; i < buttons.length; i++) {
        buttons[i].id = buttons[i].id + i;
    }


    //make the id's name different for button texts id's
     buttons = document.querySelectorAll('#evn');
    for ( i = 0; i < buttons.length; i++) {
        buttons[i].id = buttons[i].id + i;
    }

</script>


</body>
</html>



