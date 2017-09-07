"use strict";

// Optional. You will see this name in eg. 'ps' or 'top' command
process.title = 'node-notification';

// Port where we'll run the websocket server
var webSocketsServerPort = 1337;

// websocket and http servers
var webSocketServer = require('websocket').server;
var http = require('http');

/**
 * Global variables
 */
// latest 100 messages
var history = [];
// list of currently connected clients (users)
//var clients = [];
var clients = {};
/**
 * Helper function for escaping input strings
 */
function htmlEntities(str) {
    return String(str)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

// Array with some colors
var colors = ['red', 'green', 'blue', 'magenta', 'purple', 'plum', 'orange'];
// ... in random order
colors.sort(function (a, b) {
    return Math.random() > 0.5;
});

/**
 * HTTP server
 */
var server = http.createServer(function (request, response) {
    // Not important for us. We're writing WebSocket server,
    // not HTTP server
});
server.listen(webSocketsServerPort, function () {
    console.log((new Date()) + " Server is listening on port "
        + webSocketsServerPort);
});

/**
 * WebSocket server
 */
var wsServer = new webSocketServer({
    // WebSocket server is tied to a HTTP server. WebSocket
    // request is just an enhanced HTTP request. For more info
    // http://tools.ietf.org/html/rfc6455#page-6
    httpServer: server
});

// This callback function is called every time someone
// tries to connect to the WebSocket server
wsServer.on('request', function (request) {
    console.log((new Date()) + ' Connection from origin '
        + request.origin + '.');

    // accept connection - you should check 'request.origin' to
    // make sure that client is connecting from your website
    // (http://en.wikipedia.org/wiki/Same_origin_policy)
    var connection = request.accept(null, request.origin);
    // we need to know client index to remove them on 'close' event
  //  var index = clients.push(connection) - 1;
    var userName = false;
    console.log((new Date()) + ' Connection accepted.');



    // user sent some message -- msg from client --
    connection.on('message', function (message) {
        if (message.type === 'utf8') {
            if (userName === false) {
                // remember user name
                userName = htmlEntities(message.utf8Data);
                console.log(userName);
                //store connection infos in object
                if(userName in clients){
                    delete clients[userName];
                    clients[userName] = connection;
                } else{
                    clients[userName] = connection;
                }

            } else {
                var owner = htmlEntities(message.utf8Data);

                var json = JSON.stringify({type: 'notify', data: userName});
                    clients[owner].sendUTF(json);
            }

        }

    });


    // user disconnected
    connection.on('close', function (connection) {
        if(userName !== false){
            delete clients[userName];
            var size = Object.size(clients);
        }
    });
});



Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};