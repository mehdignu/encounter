$(function () {


    "use strict";
    // for better performance - to avoid searching in DOM
    //declare variables
    var userName = $('#userName').val();

    // if user is running mozilla then use it's built-in WebSocket
    window.WebSocket = window.WebSocket || window.MozWebSocket;

    // if browser doesn't support WebSocket, just show
    // some notification and exit
    if (!window.WebSocket) {
        content.html($('<p>',
            {text: 'Sorry, but your browser doesn\'t support WebSocket.'}
        ));
        input.hide();
        $('span').hide();
        return;
    }

    // open connection
    var connection = new WebSocket('ws://127.0.0.1:1337');

    connection.onopen = function () {

        connection.send(userName);
    };

    connection.onerror = function (error) {
        // just in there were some problems with connection...

    };

    // most important part - incoming messages -- from server --
    connection.onmessage = function (message) {
        // try to parse JSON message. Because we know that the server
        // always returns JSON this should work without any problem but
        // we should make sure that the massage is not chunked or
        // otherwise damaged.

        try {
            var json = JSON.parse(message.data);
        } catch (e) {
            console.log('Invalid JSON: ', message.data);
            return;
        }


        if (json.type === 'notifyAccepted') {


            var x = json.data;
            if (x !== userName)
                alert(x); //supposed to be notification alert used instead x is username of requester

        }

        if (json.type === 'notifyRequest') {


            var x = json.data;
            if (x !== userName)
                alert('notified from '+x); //supposed to be notification alert used instead x is username of requester

        }

    };

    /**
     * send or delete encounter on the front page or enter the encounter
     */
    $('.ask').bind("click", function () {

        //get creator of the event to send him a request notification
        var id = $(this).attr('id');
        var owner = $('#owner' + id).val();
        var eventId = $('#eventid' + id).val();
        var buttonText = $('#reqText' + id).text();





        //  connection.send(owner); //send to server to notify user that someone want to ask to join event

        if (buttonText === 'requested') {

            //cancel request from database
            var dataString = {"eventid": eventId, "owner": owner, "username": userName};

            $('#reqText' + id).text('Ask To Join');


            $.ajax({
                type: "POST",
                url: "cancelRequest.php",
                data: {'request': JSON.stringify(dataString)},
                cache: false,

                success: function (html) {
                    //alert(html);
                }
            });


        } else if (buttonText === 'Ask To Join') {

            var data = {"type": 'ask', "owner": owner};

            var notifyAsk = JSON.stringify(data);

            connection.send(notifyAsk);

            //save request to database
            var dataString = {"eventid": eventId, "owner": owner, "username": userName};


            $('#reqText' + id).text('requested');

            $.ajax({
                type: "POST",
                url: "request.php",
                data: {'request': JSON.stringify(dataString)},
                cache: false,

                success: function (html) {
                  //  alert(html);
                }
            });

        } else if (buttonText === 'Enter encounter') {


            var id = $('#evn'+id).val();
             window.location.href = "groupMessages.php?id="+id;

        }
    });



    $('.encOwn').bind("click", function () {



            var id = $('#evn'+id).val();
            window.location.href = "groupMessages.php?id="+id;


    });


    var notiBody = $('#notificationsBody');

    /**
     * this comes from the php and it's a elements when the user accept the rquest notify the requester
     */
    notiBody.on("click", ".acceptReq", function () {
        var id = $(this).attr('id');
        var reqID = $('#reqID' + id).val(); // requestID
        var requester = $('#requester' + id).val();
        var dataString = {"eventid": reqID};

        $.ajax({
            type: "POST",
            url: "acceptRequest.php",
            data: {'request': JSON.stringify(dataString)},
            cache: false,

            success: function (html) {
                //alert(html);
            }
        });


        //notify user that request is accepted
        var data = {"type": 'accepted', "requester": requester};
        var notifyAccept = JSON.stringify(data);
        connection.send(notifyAccept); //send notification that the request is accepted


        $('#requestElm' + id).remove(); //remove element from dom

    });



    notiBody.on("click", ".denyReq", function () {

        var id = $(this).attr('id');
        var reqID = $('#reqID' + id).val(); // requestID

        var dataString = {"eventid": reqID};
        $.ajax({
            type: "POST",
            url: "denyRequest.php",
            data: {'request': JSON.stringify(dataString)},
            cache: false,

            success: function (html) {
                //alert(html);
            }
        });

        $('#elm' + id).remove(); //remove element from dom


    });


    $('#messagessBody').on('click', '.msgsGrp', function () {

        var id  = this.id;

        window.location.href = "groupMessages.php?id="+id;
    });


    //showing the requests dynamically
    function getRequests() {

        var dataString = {"userName": userName};

        $.ajax({
            type: "POST",
            url: "../php/getReqNoti.php",
            data: {'request': JSON.stringify(dataString)},
            cache: false,
            success: (function (result) {

                var notiCount = $("#notification_count");

                if(result == 0){
                    notiCount.hide(); // hide and then change the value to zero
                    notiCount.val(result);
                    notiCount.text(result);
                } else {
                    notiCount.show();
                    notiCount.val(result);
                    notiCount.text(result);
                }


            })
        })


        $.ajax({
            type: "POST",
            url: "../php/getEncNoti.php",
            data: {'request': JSON.stringify(dataString)},
            cache: false,
            success: (function (result) {

                var msgCount = $("#messages_count");
                if(result == 0){
                    msgCount.hide(); // hide and then change the value to zero
                    msgCount.val(result);
                    msgCount.text(result);
                } else {
                    msgCount.show();
                    msgCount.val(result);
                    msgCount.text(result);
                }


            })
        })



        var dataString = {"userName": userName};

        $.ajax({
            type: "POST",
            url: "../php/fetch.php",
            data: {'request': JSON.stringify(dataString)},
            cache: false,
            success: (function (result) {
                $("#notificationsBody").html(result);
            })
        })

        $.ajax({
            type: "POST",
            url: "../php/fetchEncounters.php",
            data: {'request': JSON.stringify(dataString)},
            cache: false,
            success: (function (result) {
                $("#messagessBody").html(result);
            })
        })

    };

    getRequests(); // To output when the page loads
    setInterval(getRequests, (2 * 1000));



});

