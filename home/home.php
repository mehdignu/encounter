<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.html");
}
session_regenerate_id();

include_once '../php/show.php';


?>

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
    <script src="./notify.js"></script>
    <link rel="stylesheet" href="../css/new.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css?family=Lobster|Bitter|Cabin|Arimo" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


    <style>

        body {
            background-color: #E6E8EB;
        }

        .red {
            background-color: #400000;
        }

        footer {
            position: relative;
            bottom: 0;
            background-color: #555;
            color: white;
            padding: 5px;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            /*          z-index: 1000;*/
            min-height: 200px;
            display: none;
            min-width: 250px;
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
            max-height:250px;/* you can change as you need it */
            overflow:auto;
        }

        .dropdown-item:hover{

            background-color: #E2E5E8;

        }


    </style>

</head>
<body>

<!-- navuigation -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top" style="font-family: Arimo, sans-serif">


    <!--
       <a class="navbar-brand" href="./home.php" style="font-family: Lobster;">encounter</a>
   -->
    <!--
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
            aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>-->

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto" style="margin-left: 14%;">

            <!--
                        <li id="notifications">
                            <a class="nav-link" href="./home.php"
                               aria-haspopup="true" aria-expanded="false"><i class="fa fa-home"></i> Home </a>
                        </li>
            -->


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


            <li class="nav-item dropdown" style="font-family: Arimo;">
                <a class="nav-link" id="dropdown01" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> <?php echo getUserName($_SESSION['username']); ?> </a>
                <div class="dropdown-menu dropdown-menu-right" id="dropit" aria-labelledby="dropdown01"
                     style="min-width: 10px;min-height: 20px">
                    <a class="dropdown-item" href="setProfile.php?id=<?php echo getId($_SESSION['username']) ?>">Settings</a>
                    <a class="dropdown-item" href="../php/logout.php">logout</a>
                </div>
            </li>
        </ul>


    </div>


</nav>

<input type="hidden" id="userName" value="<?php echo $_SESSION['username'] ?>">


<div class="container-fluid" style="margin-top: 55px; padding:0">


    <div class="row" style="margin:0;min-height: 90vh">


        <div class="col-sm-2"
             style="position:fixed;height:100%;padding-top: 6%; margin-left: 14%;font-family: 'Cabin', sans-serif;">

            <div class="card">
                <?php $allowd = allowedToCreate($_SESSION['username']);
                if ($allowd < 3) {
                    ?>
                    <a class="waves-effect waves-light btn-largemimi" href="create.php"><i class="material-icons left">add_circle</i>Create
                    </a>

                <?php } else { ?>
                    <a class="waves-effect waves-light btn-largemimi disabled" href="create.php"><i
                                class="material-icons left">add_circle</i>create </a>
                    <small style="background-color: #E2E5E8; ">You can create only 3 encounters</small>
                <?php } ?>

            </div>


            <div class="row">
                <div class="col s12 m6">
                    <div class="card-panel blue-grey darken-1">
                        <span class="white-text">Your encounters:
     </span>
                    </div>
                </div>
            </div>

            <?php $enc = getOwnEncounters($_SESSION['username']);
            if ($enc) {

                while ($row = mysqli_fetch_assoc($enc)) {

                    $id = $row['id'];

                    ?>

                    <div class="row">
                        <div class="col s12 m6">
                            <div class="card blue-grey darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title" style="font-family: 'Arimo', sans-serif;">

                                        <h5>   <?php echo $row['Title'] ?></h5>

                                    </span>
                                    <p><?php echo $row['Description'] ?></p>
                                </div>
                                <div class="card-action">
                                    <a href="groupMessages.php?id=<?php echo $id ?>">Visit</a>
                                    <a href="editEncounter.php?id=<?php echo $id ?>">Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <?php
                }
            } else {
                echo 'you have created no encounters yet';
            }

            ?>


        </div>
        <div class="col-sm-5 col-sm-5 offset-sm-4 col-md-5">
            <br>

            <?php

            $result = isChecked($_SESSION['username']);
            if ($result == 0) {

                ?>

                <div class="jumbotron" id="dismissMe">
                    <h1 class="display-3">Welcome to encounter !</h1>
                    <p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra
                        attention to featured content or information.</p>
                    <hr class="my-4">
                    <p>It uses utility classes for typography and spacing to space content out within the larger
                        container.</p>
                    <p class="lead">
                        <a class="btn btn-primary btn-lg" href="#" id="dismiss" role="button">Dismiss</a>
                    </p>
                </div>
                <?php

            }

            ?>
            <br>

            <h1 style="font-family: 'Lobster', serif;">Encounters in <?php echo getCity($_SESSION['username']) ?></h1>
            <br>


            <?php

            $result = eventsArray($_SESSION['username']);


            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {

                    $month = date('jS F Y', strtotime($row['Date']));

                    ?>
                    <h4 style="width: 40rem;font-family: 'Bitter', serif;"> <?php echo $month ?></h4>
                    <hr style="width: auto">
                    <!-- <div class="card mx-auto " style="width: 40rem;font-family: 'Bitter', serif;">-->
                    <div class="card mx-auto " style="font-family: 'Bitter', serif;">
                        <!--
                        <img class="card-img-top" src="..." alt="Card image cap">
                        -->
                        <div class="card-block">

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <h4 class="card-title">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>

                                        <?php printf("%s\n", $row["Title"]); ?>

                                    </h4>
                                </li>
                                <li class="list-group-item">
                                    <h5>Description</h5>
                                    <p class="card-text">
                                        <?php printf("%s\n", $row["Description"]); ?>
                                    </p>
                                </li>
                                <li class="list-group-item">
                                    <p>
                                        <i class="fa fa-user-plus" aria-hidden="true"></i>

                                        <?php
                                        printf(" %s of %s people will encounter\n", $row["particNum"],
                                            $row["Max"]);

                                        ?>
                                    </p>

                                    <?php
                                    $rowww = getAttenders($row['id']);


                                    for ($i = 0; $i < count($rowww); $i++) {
                                        $exists = glob("/Applications/XAMPP/xamppfiles/htdocs/encounter/user_uploads/" . $rowww[$i]['attenders'] . ".*");

                                        if (count($exists)) {
                                            ?>

                                                <img class='rounded-circle' height="60px" width="60px"
                                                     src='../user_uploads/<?php echo $rowww[$i]["attenders"] ?>.jpeg'/>
                                            <?php
                                        }

                                        ?>


                                    <?php } ?>

                                </li>

                            </ul>

                        </div>

                        <?php $owner = getOwner($row['owner']) ?>
                        <?php $isRequested = isRequested($_SESSION['username'], $row['id']) ?>
                        <?php $isAccepted = isAccepted($_SESSION['username'], $row['id']) ?>
                        <input type="hidden" id="evn" value="<?php echo $row['id'] ?>">


                        <br/>
                        <ul class="list-group list-group-flush">

                            <?php
                            $time = date("g:i a", strtotime($row['Time']));
                            ?>
                            <li class="list-group-item"><i class="fa fa-clock-o"></i> Time: <?php echo $time ?></li>

                        </ul>
                        <div class="card-block text-center ask">
                            <?php if ($isAccepted == true) { ?>


                                <button class="waves-effect waves-light btn-largemimi"
                                        style="width=100%!important;background-color: #4caf50">

                                    <i class="material-icons left">drafts</i> <span
                                            id="reqText"><?php echo 'Enter encounter'; ?></span>

                                </button>


                            <?php } else { ?>
                                <?php if ($isRequested == true) { ?>

                                    <button class="waves-effect waves-light btn-largemimi"
                                            style="width=100%!important;background-color: #fdd835">

                                        <i id="iconText" class="material-icons left">label</i> <span
                                                id="reqText"><?php echo 'requested'; ?></span>

                                    </button>


                                <?php } else {

                                    if ($row["particNum"] == $row["Max"]) {
                                        ?>


                                        <button class="waves-effect waves-light btn-largemimi"
                                                style="width=100%!important;background-color: #78909c">

                                            <i class="material-icons left">disc_full</i> <span
                                                    ><?php echo 'Event is full'; ?></span>

                                        </button>
                                        <?php
                                    } else {
                                        ?>

                                        <button class="waves-effect waves-light btn-largemimi" id="iconButt"
                                                style="width=100%!important;background-color: #2962ff">

                                            <i id="iconText" class="material-icons left">add</i> <span
                                                    id="reqText"><?php echo 'Ask To Join'; ?></span>

                                        </button>

                                    <?php }
                                }
                            } ?>

                            <input type="hidden" class="own" id="owner" value="<?php echo $owner ?>">
                            <input type="hidden" class="evn" id="eventid" value="<?php echo $row['id'] ?>">

                        </div>


                    </div>

                    <br>
                    <!--   <hr style="width: 60em"> -->
                    <br>
                    <?php

                }
            } else {
                echo 'no encounters yet';
            }

            ?>


            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <?php

                    pagination($_SESSION['username']);
                    ?>
                </ul>
            </nav>
        </div>


        <div class="col-sm-2" style="padding-top: 3%; margin-right: 6%;right: 0; position: fixed;height:100%;">
            <!--
                        <div class="card"
                             style=" height:5em;padding-top:10px;margin-top:30px;padding-left:5px;">
                            <div class="card-block">
                                Ads
                            </div>
                        </div>-->


        </div>


    </div>

    <footer class="container-fluid text-center col-sm-12 navbar-dark bg-dark  ">
        <p>
            <small>&copy; Copyright 2017, encounter</small>
        </p>
    </footer>

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



