$(function () {

    //var status   = document.getElementById("status").innerHTML;
    // for better performance - to avoid searching in DOM
    var content = $('#content');
    var input = $('#input');
    var status = $('#status');

// my color assigned by the server
    var myColor = false;
    // my name sent to the server
    var myName = document.getElementById("status").innerHTML;


    // if user is running mozilla then use it's built-in WebSocket
    window.WebSocket = window.WebSocket || window.MozWebSocket;

    var connection = new WebSocket('ws://127.0.0.1:1337');

    connection.onopen = function () {
        // connection is opened and ready to use
        //  connection.send(status);
        //  myName = status; //assign username

        connection.send(myName);

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

        if (json.type === 'color') {
            myColor = json.data;
            status.text(myName + ': ').css('color', myColor);
            input.removeAttr('disabled').focus();
            // from now user can start sending messages
        } else if (json.type === 'history') { // entire message history
            // insert every single message to the chat window
            for (var i = 0; i < json.data.length; i++) {
                addMessage(json.data[i].author, json.data[i].text,
                    json.data[i].color, new Date(json.data[i].time));
            }
        } else if (json.type === 'message') { // it's a single message
            // let the user write another message
            input.removeAttr('disabled');


            var text = JSON.parse(json.data.text.replace(/(&quot\;)/g,"\"")).message;

            addMessage(json.data.author, text,
                json.data.color, new Date(json.data.time));
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
            // send the message
            var dataString = {'type': 'chatMsg', 'message': msg};
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

    /**
     * Add message to the chat window
     */
    function addMessage(author, message, color, dt) {

        content.prepend('<p><span style="color:' + color + '">'
            + author + '</span> @ ' + (dt.getHours() < 10 ? '0'
                + dt.getHours() : dt.getHours()) + ':'
            + (dt.getMinutes() < 10
                ? '0' + dt.getMinutes() : dt.getMinutes())
            + ': ' + message + '</p>');
    }


});