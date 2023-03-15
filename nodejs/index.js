import {createServer} from "http";
import path from 'path';
import {Server} from "socket.io"
import dotenv from 'dotenv';
import app from "express";
import Redis from 'ioredis';

dotenv.config({path: path.resolve(process.cwd(), '.env')});

var redis = new Redis(process.env.REDIS_HOST, process.env.REDIS_PORT);
var server = createServer(app)
var io = new Server(server);

redis.subscribe('test-channel', function (err, count) {
});
redis.on('message', function (channel, message) {
    console.log('Message Recieved: ' + message);
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
});

// io.use(
//     authorize({
//         secret: process.env.JWT_SECRET,
//         algorithms: [process.env.JWT_ALGO]
//     })
// )
io.on('connection', async function (socket) {
    io.emit('message', {
        'message': 'You are connected'
    })
    socket.on('users.add', function (message) {
        console.log(message);
        io.to().emit('users.add', message);
    });
    socket.on('users.update', function (message) {
        console.log(message);
        io.emit('users.add', message);
    });
    socket.on('users.delete', function (message) {
        io.emit('users.add', message);
    });
    socket.on('disconnect', () => {
        console.log('disconnected')
    });
});

server.listen(process.env.WEBSOCKET_PORT, function () {
    console.log(`Listening on port ${process.env.WEBSOCKET_PORT}`);
    console.log(`http://localhost:${process.env.WEBSOCKET_PORT}`);
});
