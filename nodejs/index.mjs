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

var prefix = process.env.REDIS_PREFIX || ((process.env.APP_NAME.toLowerCase() || 'laravel') + '_database_');
// io.use(
//     authorize({
//         secret: process.env.JWT_SECRET,
//         algorithms: [process.env.JWT_ALGO]
//     })
// )

console.log(prefix);
redis.subscribe(prefix + 'users.add', function (err, count) {
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
redis.on("message", (channel, message) => {
    console.log(`Received ${message} from ${channel}`);
    let data = JSON.parse(message);
    if (data.event === 'App\\Events\\ConnectedEvent') {
        io.except(data.socket).emit('users.add', {
            user: data.data.user
        });
        io.to(data.socket).emit('users.add', message)
    }
    if (data.event === 'App\\Events\\UserUpdateEvent') {
        io.except(data.socket).emit('users.update', {
            user: data.data.user
        });
        io.to(data.socket).emit('users.add', message)
    }
});

io.on('connection', async function (socket) {
    let message = {
        socket: socket.id
    }
    redisPublish.publish(prefix + 'connected', JSON.stringify(message));

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
        message = {
            socket: socket.id
        };
        redisPublish.publish(prefix + 'disconnected', JSON.stringify(message));
        io.emit('users.delete', message);
    });

});

server.listen(process.env.WEBSOCKET_PORT, function () {
    console.log(`Listening on port ${process.env.WEBSOCKET_PORT}`);
    console.log(`http://localhost:${process.env.WEBSOCKET_PORT}`);
});
