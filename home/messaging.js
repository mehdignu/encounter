$(function () {

    //var status   = document.getElementById("status").innerHTML;
    // for better performance - to avoid searching in DOM
    var content = $('#content');
    var input = $('#input');
    var status = $('#status');
    var i =0;

    // my name sent to the server
    var myName = document.getElementById("status").value;


    // if user is running mozilla then use it's built-in WebSocket
    window.WebSocket = window.WebSocket || window.MozWebSocket;

    var connection = new WebSocket('ws://127.0.0.1:1337');

    connection.onopen = function () {
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


                var res = '[' + res + ']';
                res = JSON.parse(res);

                for(var i = 0; i < res.length; i++) {

                    if(res){
                        var obj = res[i];
                        var author = obj.author;
                        var message = obj.message;
                        var dt = obj.dt;

                        addMessage(author,message,new Date(dt));
                    }
                }

            })
        })


        connection.send(myName);
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

         if (json.type === 'history') { // entire message history
            // insert every single message to the chat window
            for (var i = 0; i < json.data.length; i++) {
                addMessage(json.data[i].author, json.data[i].text, new Date(json.data[i].time));
            }
        } else if (json.type === 'message') { // it's a single message
            // let the user write another message
            input.removeAttr('disabled');


            var text = JSON.parse(json.data.text.replace(/(&quot\;)/g,"\"")).message;

            addMessage(json.data.author, text, new Date(json.data.time));
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
    function addMessage(author, message, dt) {

        content.append('<p><span>'
            + author + '</span> @ ' + (dt.getHours() < 10 ? '0'
                + dt.getHours() : dt.getHours()) + ':'
            + (dt.getMinutes() < 10
                ? '0' + dt.getMinutes() : dt.getMinutes())
            + ': ' + message + '</p>');

        //focus again on the input field
        document.getElementById("input").focus();

        //scroll to the bottom of the page
        var d = $('#content');
        d.scrollTop(d.prop("scrollHeight"));
    }



});