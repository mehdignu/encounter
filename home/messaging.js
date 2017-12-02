$(function () {

    //var status   = document.getElementById("status").innerHTML;
    // for better performance - to avoid searching in DOM
    var content = $('#content');
    var input = $('#input');
    var status = $('#status');
    var i =0;
    var userName = $('#userName').val();

    // my name sent to the server
    var myName = document.getElementById("status").value;


    // if user is running mozilla then use it's built-in WebSocket
    window.WebSocket = window.WebSocket || window.MozWebSocket;

    var connection = new WebSocket('ws://127.0.0.1:1337');

    connection.onopen = function () {

        connection.send(userName);

        // connection is opened and ready to use
        //  connection.send(status);
        //  myName = status; //assign username

        var id = getID();
        $.ajax({
            type: "POST",
            url: "../php/getLatest.php",
            data: {'id': JSON.stringify(id)},
            cache: false,
            success: (function (result) {

                var res =JSON.parse(result);
                var tmp = "";

                var res = '[' + res + ']';
                res = JSON.parse(res);

                for(var i = 0; i < res.length; i++) {

                    if(res){
                        var obj = res[i];
                        var author = obj.author;
                        var message = obj.message;
                        var dt = obj.dt;

                        var dateMe = "";

                        var today = new Date();
                        var dd = today.getDate();


                        if(dd > new Date(dt).getDate() && tmp !== new Date(dt).toDateString()){

                            dateMe = new Date(dt).toDateString();
                            tmp = new Date(dt).toDateString();

                        } else if(dd ===  new Date(dt).getDate() && tmp !== new Date(dt).toDateString()){
                            dateMe = 'today';
                            tmp = new Date(dt).toDateString();

                        } else {
                            dateMe = "";

                        }

                        addMessage(author,message,new Date(dt), dateMe);
                    }
                }

            })
        })


      //  connection.send(myName);
        input.removeAttr('disabled').focus();

    };

    connection.onerror = function (error) {
        // an error occurred when sending/receiving data
    };

    connection.onmessage = function (message) {
        // try to decode json (I assume that each message
        // from server is json)



        try {
            var json = JSON.parse(message.data);
        } catch (e) {
            console.log('This doesn\'t look like a valid JSON: ',
                message.data);
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


         if (json.type === 'history') { // entire message history
            // insert every single message to the chat window
            for (var i = 0; i < json.data.length; i++) {
                addMessage(json.data[i].author, json.data[i].text, new Date(json.data[i].time));
            }
        } else if (json.type === 'message') { // it's a single message
            // let the user write another message
            input.removeAttr('disabled');


            var text = JSON.parse(json.data.text.replace(/(&quot\;)/g,"\"")).message;


            var realname = '';
             $.get('realName.php', { auth: json.data.author },  function(data) {

                 addMessage(data, text, new Date(json.data.time),'');

             });


        } else {
            console.log('Hmm..., I\'ve never seen JSON like this:', json);
        }

        // handle incoming message
    };


    /**
     * Send message when user presses Enter key
     */
    input.keydown(function (e) {
        if (e.keyCode === 13) {
            var msg = $(this).val();
            if (!msg) {
                return;
            }


            //get the event id
            var id=getID();

            //creating the current date/time
            var dateTime = new Date();
            dateTime.toISOString();

            //save the message in the database
            $.ajax({
                type: "POST",
                url: "../php/saveMsg.php",
                data: {'author':JSON.stringify(myName) , 'message': JSON.stringify(msg) , 'dt': JSON.stringify(dateTime), 'id': JSON.stringify(id)},
                cache: false,

                success: function (html) {

                }
            });

            // send the message
            var dataString = {'type': 'chatMsg', 'message': msg,'id': id};
            var chatMessage = JSON.stringify(dataString);

            connection.send(chatMessage);

            //JSON.parse(message.utf8Data).type
            $(this).val('');
            // disable the input field to make the user wait until server
            // sends back response
            input.attr('disabled', 'disabled');

        }
    });


    /**
     * This method is optional. If the server wasn't able to
     * respond to the in 3 seconds then show some error message
     * to notify the user that something is wrong.
     */
    setInterval(function () {
        if (connection.readyState !== 1) {
            status.text('Error');
            input.attr('disabled', 'disabled').val(
                'Unable to communicate with the WebSocket server.');
        }
    }, 3000);


    function getID(){
        var url = window.location.href;
        var id = url.substring(url.indexOf('?id=')+1);
        id = id.substring(3);
        return id;
    }

    /**
     * Add message to the chat window
     */
    function addMessage(author, message, dt, dateMe) {

        if(dateMe != ''){

            content.append('<div class="dateme"><h5 id="ew">'+dateMe+'</h5></div><div class="line"></div><br> ');

        }

        content.append('<p><span>'
            + author + '</span><small> @ ' + (dt.getHours() < 10 ? '0'
                + dt.getHours() : dt.getHours()) + ':'
            + (dt.getMinutes() < 10
                ? '0' + dt.getMinutes() : dt.getMinutes())
            +  '</small>: ' + message + '</p>');

        //focus again on the input field
        document.getElementById("input").focus();

        //scroll to the bottom of the page
        var d = $('#content');
        d.scrollTop(d.prop("scrollHeight"));
    }


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
                // alert(html);
                $("#messagessBody").html(result);

            })
        })

    };

    getRequests(); // To output when the page loads
    setInterval(getRequests, (2 * 1000));




});