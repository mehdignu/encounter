<?php

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.html");
    return false;
}
session_regenerate_id();

include_once '../php/show.php';


$requesterId = $_GET['id'];
if (preg_match("/[a-z]/i", $requesterId) || preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $requesterId)) {
    header("Location: ../index.html");
    return false;
}

$stmt = $connection->prepare('SELECT id FROM users WHERE UserName= ?');
$stmt->bind_param('s', $_SESSION['username']);

$stmt->execute();

$result = $stmt->get_result();

$row = mysqli_fetch_row($result);

$id = $row[0];

if ($id != $requesterId) {
    header("Location: ./home.php");
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

    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <script src="./frontend.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Barlow+Condensed" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/new.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster|Bitter|Cabin|Arimo" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lobster|Bitter|Cabin|Arimo" rel="stylesheet">

    <link href="../css/profile.css" rel="stylesheet" type="text/css"/>
    <script src="./notify.js"></script>

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
        }

        .bg-company-red {
            background-color: #400000;
        }

        #content {
            padding: 5px;
            background: #ffffff;
            overflow: auto;
            margin-bottom: 10px;
            height: 100%
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
                   aria-haspopup="true" aria-expanded="false"><i
                            class="fa fa-user"></i> <?php echo getUserName($_SESSION['username']); ?> </a>
                <div class="dropdown-menu dropdown-menu-right" id="dropit" aria-labelledby="dropdown01"
                     style="min-width: 10px;min-height: 20px">
                    <a class="dropdown-item" href="setProfile.php?id=<?php echo getId($_SESSION['username']) ?>"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a>
                    <a class="dropdown-item" href="../php/logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Sign-out</a>
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


                    <?php

                    $result = fillProfile($requesterId);


                    if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {


                    ?>
                    <img id="blah" src="../user_uploads/<?php echo $row['ImageName'] ?>"/>




                    <!--  <input type='file' onchange="readURL(this);" accept="image/png,image/jpg,image/jpeg" /> -->

                    <div id="wrapper"><br>
                        <form name="register" id='register' action='saveProfile.php'
                              method='post' accept-charset='UTF-8' enctype="multipart/form-data" onkeypress="return event.keyCode != 13;">




                                <label for="files" id="demo"></label>

                                <input id="files" name="files" style="visibility:block;position:absolute;" type="file" onchange="uploaded()">



                                    <label><b>FirstName</b></label>
                                    <input name="FirstName" class="form-control" type="text" placeholder="First name"
                                           maxlength="15"
                                           value="<?php echo $row['FirstName'] ?>" required><br>

                                    <label><b>LastName</b></label>
                                    <input name="LastName" class="form-control" type="text" placeholder="Last name"
                                           maxlength="15"
                                           value="<?php echo $row['LastName'] ?>" required><br>

                                    <div class="form-group">
                                        <label><b>About me</b></label>
                                        <textarea class="form-control text" placeholder="Description of yourself"
                                                  cols="35"
                                                  maxlength="120" name="about" rows="3"
                                                  required><?php echo $row['about'] ?></textarea>
                                    </div>


                                    <label><b>Gender</b></label>
                                    <select class="form-control" name="gender" id="gender" style="width: 5em">
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>

                                    </select>
                                    <input type="hidden" id="gend" value="<?php echo $row['gender'] ? 1 : 2 ?>">

                                    <br>
                                    <label><b>City</b></label>
                                    <input name="city" class="form-control" type="text" placeholder="Title here"
                                           value="<?php echo $row['city'] ?>" required><br>

                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                    <button type="button" class="btn btn-secondary" onClick="this.form.reset()">Reset
                                    </button>

                                <?php }
                            }
                            ?>
                        </form>
                    </div>

                </div>

                <div class="card-block" style="position: absolute; bottom: 0;width: 100%;">
                    <div class="form-inline">

                    </div>
                </div>
            </div>

        </div>

        <div class="col-sm-2" style="background-color:#E2E5E8;right: 0; position: fixed;height:100%;padding-top: 20px;">

            <button type="button" class="btn btn-danger"
                    onclick="location.href = '../php/deleteMe.php?id=<?php echo $id ?>'">Delete Account
            </button>

        </div>
        <br>


    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
            integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
            integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
            crossorigin="anonymous"></script>


    <script>
        function uploaded(){

            var x = document.getElementById("files");
            var txt = "";
            var name="";
            if ('files' in x) {
                if (x.files.length == 0) {
                    txt = "Select one or more files.";
                } else {
                    for (var i = 0; i < x.files.length; i++) {
                        txt += "<br><strong>" + (i+1) + ". file</strong><br>";
                        var file = x.files[i];
                        if ('name' in file) {
                            txt += "name: " + file.name + "<br>";
                            name += "name: " + file.name + "<br>";
                        }
                        if ('size' in file) {
                            txt += "size: " + file.size + " bytes <br>";
                        }
                    }
                }
            }
            else {
                if (x.value == "") {
                    txt += "Select one or more files.";
                } else {
                    txt += "The files property is not supported by your browser!";
                    txt  += "<br>The path of the selected file: " + x.value; // If the browser does not support the files property, it will return the path of the selected file instead.
                }
            }

            // To Display
            if (x.files && x.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(x.files[0]);
            }
            document.getElementById("demo").innerHTML = name.substring(0,8)+ '...';
        }


        $(document).ready(function () {




            var x = $('#gend').val();

            $('#gender').val(x);
            $('#gender').change();

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


