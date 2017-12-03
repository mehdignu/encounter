<?php

session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index.html");
}

session_regenerate_id();
include_once '../php/show.php';


/*
 * all dis to checkout if the user is allowed to visit the chat page (accepted to the encounter event)
 */


$EventId = $_GET['id'];

if(preg_match("/[a-z]/i", $EventId) || preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $EventId)){
    header("Location: ../index.html");
}

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
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script
        src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
        crossorigin="anonymous"></script>

    <script src="./frontend.js"></script>
    <script src="./notify.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.0-RC3/css/bootstrap-datepicker.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <link href="https://fonts.googleapis.com/css?family=Lobster|Bitter|Cabin|Arimo" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/new.css">


    <!-- maps loccation choose -->

    <script type="text/javascript" src='http://maps.google.com/maps/api/js?libraries=places&key=AIzaSyBCVuS83u7FTWYsPCpKN8QoVJeIiSmmo1Y'></script>
    <script src="../js/locationpicker.jquery.js"></script>

    <title>edit encounter</title>
    <style>



        footer {
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
        }
    </style>
</head>
<body>

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

<div class="container-fluid mx-auto" style="padding-bottom: 5px;padding-top: 30px;">
    <div class="row">

        <div class="col-sm-3" style="background-color:#f1f1f1;position:fixed;height:100%;padding-top: 20px;">

            <!-- <h4>Your upcoming encounters :</h4>

             <p><a href="#">Link</a></p>
             <p><a href="#">Link</a></p>
             <p><a href="#">Link</a></p>-->

            <div id="loc" style="width: 100%; height: 25em; margin-top: 15px;" ></div><br />



        </div>
        <div class="col-sm-6 col-sm-6 offset-sm-3 col-md-6 mx-auto" style="margin-top: 25px;">



            <form name="register" id='register' action='saveChanges.php' method='post' accept-charset='UTF-8' onkeypress="return event.keyCode != 13;">

                <?php $enc = getEncounter($EventId);
                if ($enc) {
                while ($row = mysqli_fetch_assoc($enc)) {


                    ?>

                    <label><b>Title</b></label>
                    <input name="title" class="form-control" type="text" placeholder="Title here"
                         value="<?php echo $row['Title'] ?>"  required><br>


                    <div class="form-group">
                        <label><b>Description</b></label>
                        <textarea class="form-control text" placeholder="Description of the encounter" cols="35"
                                  maxlength="150" name="description" rows="3" required><?php echo $row['Description'] ?></textarea>
                    </div>

                    <label><b>location </b><i>it will be shown only for the members of the encounter</i></label>
                    <input type="text" id="us3-address" name="location" class="form-control" value="<?php echo $row['Location'] ?>" required/><br>
                    <input type="hidden" name="lat" id="lat" value="<?php echo $row['lat'] ?>">
                    <input type="hidden" name="lng" id="lng" value="<?php echo $row['lng'] ?>">


                    <input type="hidden" name="id" id="id" value="<?php echo $EventId ?>">



                    <label><b>Date</b></label>
                    <p><input type="text" id="datepicker" name="date" class="form-control" value="<?php echo $row['Date'] ?>" required></p><br>

                    <label><b>Time:</b></label>

                    <div class="form-inline">

                        <?php

                        $str_time = $row['Time'];

                        $str_time = explode(':',$str_time);
                        ?>


                        <label style="margin-right: 9px;">Hour:</label>
                        <select class="form-control" name="hour" id="hour" style="width: 5em">

                        </select>
                        <input type="hidden" id="hourV" value="<?php echo $str_time[0] ?>">


                        <label style="margin-left: 9px;margin-right: 9px;"> minute:</label>
                        <select class="form-control" name="min" id="min" style="width: 5em">

                        </select>
                        <input type="hidden" id="minV" value="<?php echo $str_time[1] ?>">

                    </div><br>

                    <div class="form-inline">

                        <label style="margin-right: 9px;"><b>Maximum number of people :</b></label>
                        <select class="form-control" id="max" name="max" style="width: 5em">
                        </select>
                        <input type="hidden" id="plpV" value="<?php echo $row['Max'] ?>">

                    </div><br>

                    <label><b>Age:</b></label><br/>
                    <div class="form-inline">

                        <i style="margin-right: 9px;">Between</i>
                        <select class="form-control" id="age" name="age"  style="width: 5em">
                        </select>

                        <input type="hidden" id="ageV" value="<?php echo $row['Age'] ?>">

                        <i style="margin-left: 9px;margin-right: 9px;">and:</i>
                        <select class="form-control" id="age1" name="age1" style="width: 5em">
                        </select>

                        <input type="hidden" id="age1V" value="<?php echo $row['Age1'] ?>">

                    </div>


                    <br/>

                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-primary" onClick="this.form.reset()">Reset</button>

                    <?php
                }
                }
                ?>
                <hr>
            </form>

        </div>




    </div>


    <footer class="container-fluid text-center col-sm-12 navbar-dark bg-dark fixed-bottom">
        <p>Footer Text</p>
    </footer>


    <script>


        var x = $('#datepicker').val();

        //jquery function to select the date
        $( function() {
            $( "#datepicker" ).datepicker(


                {

                    uiLibrary: 'bootstrap4',
                    iconsLibrary: 'fontawesome',
                    minDate:new Date(),
                    defaultDate: new Date(x),
                    gotoCurrent: true





                }
            );
        } );


        $(document).ready(function () {


            var x = $('#ageV').val();
            var y = $('#age1V').val();

            $('#age').val(x);
            $('#age').change();
            $('#age1').val(y);
            $('#age1').change();

             x = $('#plpV').val();
            $('#max').val(x);
            $('#max').change();


            var x = $('#minV').val();
            var y = $('#hourV').val();

            $('#min').val(x);
            $('#min').change();
            $('#hour').val(y);
            $('#hour').change();

            //  $("#notification_count").hide();
            // $("#messages_count").hide();


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


        });



        //jquery function to select the date
        $( function() {
            $( "#datepicker" ).datepicker();
        } );
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


        //set the hours and minutes and maximum people in the dropdown
        var hour = "";
        var mins = "";
        var max = "";
        var age = "";
        var age1 = "";
        var i = 0;
        var s = '';
        for(var i = 0; i < 24; i++)
        {
            if(i <= 9){
                s= '0'+i;
                hour += "<option value="+s+">"+s+"</option>";
            } else {
                hour += "<option value="+i+">"+i+"</option>";
            }

        }
        i = 0;
        for(var i = 0; i < 60; i++)
        {
            if(i <= 9){
                s= '0'+i;
                mins += "<option value="+s+">"+s+"</option>";
            } else {
                mins += "<option value="+i+">"+i+"</option>";
            }
        }
        i = 0;
        for(var i = 2; i <= 20; i++)
        {
            max += "<option value="+i+">"+i+"</option>";
        }

        i = 0;
        for(var i = 0; i <= 70; i++)
        {
            if(i == 18)
                age += "<option value="+i+" selected>"+i+"</option>";
            age += "<option value="+i+">"+i+"</option>";
        }

        i = 0;
        for(var i = 0; i <= 70; i++)
        {
            if(i == 25)
                age1 += "<option value="+i+" selected>"+i+"</option>";
            age1 += "<option value="+i+">"+i+"</option>";
        }



        $("#hour").html(hour);
        $("#min").html(mins);
        $("#max").html(max);
        $("#age").html(age);
        $("#age1").html(age1);


    </script>

</body>
</html>

