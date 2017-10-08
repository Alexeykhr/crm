var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('redis');
var socketioJWT = require('socketio-jwt');
var env = require('dotenv').config({ path: '../.env' });
 
server.listen(6379);
console.log('Server is started');

io.on('connection', socketioJWT.authorize({
    secret: env.parsed.JWT_SECRET,
    timeout: 15000,
}));

io.on('authenticated', function (socket) {
    socket.broadcast.emit('live', socket.decoded_token.sub);

    console.log(socket.decoded_token);

    socket.emit('user-id', socket.decoded_token.exp);

    socket.on('public-my-message', function (data) {
        // socket.emit('receive-my-message', data.msg);
        socket.broadcast.emit('receive-my-message', data.msg);
    });

});

// console.log("new client connected");
// var redisClient = redis.createClient();

// redisClient.subscribe('message');
// redisClient.on("message", function(channel, message) {
//   console.log("new message in queue "+ channel);
//   socket.emit(channel, message);
// });
//
// redisClient.subscribe('messageRead');
// redisClient.on("messageRead", function(channel, message) {
//   console.log("message was read");
//   socket.emit(channel, message);
// });

// redisClient.on('message', function (val) {
//     console.log(val);
//     socket.emit('message', val);
// });
//
// socket.on('disconnect', function() {
//     redisClient.quit();
// });
