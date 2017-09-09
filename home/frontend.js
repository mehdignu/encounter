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

        if (json.type === 'notify') {
            var x = json.data;
            if(x!==userName)
                alert(x); //supposed to be notification alert used instead x is username of requester
        }


    };


    $('.ask').bind("click",function(){
        //get creator of the event to send him a request notification
        var id = $(this).attr('id');
        var owner = $('#owner'+id).val();
        var eventId = $('#eventid'+id).val();
        var buttonText =$('#reqText'+id).text();

        connection.send(owner); //send to server

        if(buttonText==='requested'){

            //cancel request from database
            var dataString = { "eventid": eventId, "owner": owner, "username": userName };

            $('#reqText'+id).text('Ask To Join');


            $.ajax({
                type: "POST",
                url: "cancelRequest.php",
                data: {'request': JSON.stringify(dataString)},
                cache: false,

                success: function(html)
                {
                    //alert(html);
                }
            });


        } else if(buttonText==='Ask To Join') {

            //save request to database
            var dataString = { "eventid": eventId, "owner": owner, "username": userName };
            $('#reqText'+id).text('requested');


            $.ajax({
                type: "POST",
                url: "request.php",
                data: {'request': JSON.stringify(dataString)},
                cache: false,

                success: function(html)
                {
                    //alert(html);
                }
            });

        }
    });


    $('.acceptReq').bind("click",function(){
        var id = $(this).attr('id');
        var reqID = $('#reqID'+id).val(); // requestID
        var dataString = { "eventid": reqID};

        $.ajax({
            type: "POST",
            url: "acceptRequest.php",
            data: {'request': JSON.stringify(dataString)},
            cache: false,

            success: function(html)
            {
                //alert(html);
            }
        });
    });

    $('.denyReq').bind("click",function(){
        var id = $(this).attr('id');
        var reqID = $('#reqID'+id).val(); // requestID

        var dataString = { "eventid": reqID};
        $.ajax({
            type: "POST",
            url: "denyRequest.php",
            data: {'request': JSON.stringify(dataString)},
            cache: false,

            success: function(html)
            {
                //alert(html);
            }
        });
    });


});

