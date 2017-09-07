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
            alert(x);
        }


    };


    $('#boo').bind("click",function(){
        var owner = $('#owner').val();
        connection.send(owner);
    });

});

