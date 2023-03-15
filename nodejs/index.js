import {createServer} from "http";
import path from 'path';
import {Server} from "socket.io"
import dotenv from 'dotenv';
import app from "express";
import Redis from 'ioredis';

dotenv.config({path: path.resolve(process.cwd(), '.env')});

var redis = new Redis(process.env.REDIS_HOST, process.env.REDIS_PORT, {
    db: process.env.REDIS_DB || 0
});
var redisPublish = new Redis(process.env.REDIS_HOST, process.env.REDIS_PORT);
var server = createServer(app)
var io = new Server(server);

// io.use(
//     authorize({
//         secret: process.env.JWT_SECRET,
//         algorithms: [process.env.JWT_ALGO]
//     })
// )

redis.subscribe('laravel_database_users.add', function (err, count) {
    if (err) {
        // Just like other commands, subscribe() can fail for some reasons,
        // ex network issues.
        console.error("Failed to subscribe: %s", err.message);
    } else {
        // `count` represents the number of channels this client are currently subscribed to.
        console.log(
            `Subscribed successfully! This client is currently subscribed to ${count} channels.`
        );
    }
});
// redis.on("message", (channel, message) => {
//     console.log(`Received ${message} from ${channel}`);
// });
redis.on("message", (channel, message) => {
    console.log(`Received ${message} from ${channel}`);
    let data = JSON.parse(message);
    if (data.event === 'App\\Events\\ConnectedEvent') {
        io.emit('users.add', message);
    }
});

io.on('connection', async function (socket) {
    let message = {
        message: 'New user connected',
        socket: socket.id
    }
    redisPublish.publish('laravel_database_connected', JSON.stringify(message));
    io.emit('message', {
        'message': 'You are connected'
    })
    socket.on('users.add', function (message) {
        console.log(message);
        io.emit('users.add', message);
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
