let http = require('http').Server();
let io = require('socket.io')(http);
let Redis = require('ioredis');

let redis = new Redis();
redis.subscribe('chat-box');
redis.on('message', function (channel, message) {
    console.log('Message recived: ' + message);
    console.log('Channel: ' + channel);
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
});

http.listen(6379, function () {
    console.log('Listening on Port 6379');
});


