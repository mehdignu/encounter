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

-->


<!doctype html>
<html lang="en">
<head>
    <title>encounter</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script
            src="https://code.jquery.com/jquery-1.12.4.min.js"
            integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
            crossorigin="anonymous"></script>

    <script src="./frontend.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">


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

            <h2>Top Navigation Example</h2>
            <p>Some content..</p>
            <a class="button button5" href="create.html">Create an encounter</a>
            <a class="button button5">Find an encounter</a>
            <br/>

            <br>

                    <h4>Friday 10 2017</h4>

            <hr>


            <?php

            $result = eventsArray();
            while ($row = mysqli_fetch_assoc($result)) {
            ?>

            <div class="card mx-auto " style="width: 35rem;">
                <!--
                <img class="card-img-top" src="..." alt="Card image cap">
                -->
                <div class="card-block">

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <h4 class="card-title">

                                <?php printf("%s\n",$row["Title"]); ?>

                            </h4>
                        </li>
                        <li class="list-group-item">
                            <h5>Description</h5>
                            <p class="card-text">
                                <?php printf("%s\n",$row["Description"]); ?>
                            </p>
                        </li>
                        <li class="list-group-item">
                            <p>3 of 5 people are attending the event</p>
                            <img class='rounded-circle' height="60px" width="60px" src='http://www.math.uni-frankfurt.de/~person/_4170854.jpg' />
                            <img class='rounded-circle' height="60px" width="60px" src='http://via.placeholder.com/350x150' />
                            <img class='rounded-circle' height="60px" width="60px" src='http://via.placeholder.com/350x150' />


                        </li>

                    </ul>



                </div>

                <?php $owner = getOwner($row['owner']) ?>
                <?php $isRequested = isRequested($_SESSION['username'], $row['id']) ?>
                <?php $isAccepted = isAccepted($_SESSION['username'], $row['id']) ?>
                <input type="hidden" id="evn" value="<?php echo $row['id'] ?>">


                <br />
                <ul class="list-group list-group-flush">
                    <li  class="list-group-item">Date/Time: 10/12/2017 - 18:15pm</li>

                </ul>
                <div class="card-block text-center ask">
                    <?php if($isAccepted == TRUE) { ?>
                    <button type="button" class="btn btn-success btn-lg btn-block" ><span id="reqText"><?php echo 'Enter encounter'; ?></span></button>
                    <?php } else { ?>
                    <?php if($isRequested == TRUE){ ?>
                        <button type="button" class="btn btn-warning btn-lg btn-block"><span id="reqText"><?php echo 'Enter encounter'; ?></span></button>

                    <?php } else { ?>

                    <button type="button" class="btn btn-primary btn-lg btn-block"><span id="reqText"><?php echo 'Ask To Join'; ?></span></button>

                    <?php } }?>

                    <input type="hidden" class="own" id="owner" value="<?php echo $owner ?>">
                    <input type="hidden" class="evn" id="eventid" value="<?php echo $row['id'] ?>">

                </div>


            </div>


                <?php

            }

            ?>

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
<!-- jQuery first, then Popper.js, then Bootstrap JS
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>


<script>
    $(document).ready(function () {


      //  $("#notification_count").hide();
       // $("#messages_count").hide();




        //notifications Requests count
        var count = document.getElementById("notification_count").innerText;
        var d1 = document.getElementById("dropdown01");

        //alert(count);
        if(count == 0){
            d1.classList.remove("active");
            $("#notification_count").hide();
        } else {
            d1.className += " active";
            $("#notification_count").show();
        }

        //notifications Encounters count
        var countNoti = document.getElementById("messages_count").innerText;
        var d2 = document.getElementById("dropdown02");
        if(countNoti == 0){
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

        //Document Click hiding the popup
      /*  $(document).click(function () {
            $("#notificationContainer").hide();
            $("#messagesContainer").hide();
        });
*/


        //Popup on click
     /*   $("#notificationContainer").click(function () {
            return false;
        });
*/


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



