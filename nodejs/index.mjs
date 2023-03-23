import {createServer} from "http";
import path from 'path';
import {Server} from "socket.io"
import dotenv from 'dotenv';
import app from "express";
import Redis from 'ioredis';

dotenv.config({path: path.resolve(process.cwd(), '.env')});

const redis = new Redis(process.env.REDIS_HOST, process.env.REDIS_PORT, {
    db: process.env.REDIS_DB || 0
});
const redisPublish = new Redis(process.env.REDIS_HOST, process.env.REDIS_PORT);
const server = createServer(app)
const io = new Server(server);

const prefix = process.env.REDIS_PREFIX || ((process.env.APP_NAME.toLowerCase() || 'laravel') + '_database_');

redis.subscribe(prefix + 'users', function (err, count) {
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

    const data = JSON.parse(message);

    if (data.event === 'App\\Events\\ConnectedEvent') {
        io.emit('users.add', data.data.user);
    }

    if (data.event === 'App\\Events\\UserListEvent') {
        io.emit('users.list', data.data.users);
    }
    if (data.event === 'App\\Events\\UserUpdateEvent') {
        io.emit('users.update', data.data.user);
    }
    if (data.event === 'App\\Events\\DisconnectedEvent') {
        io.emit('users.delete', data.data.user);
    }
});

io.on('connection', async function (socket) {
    let message = {
        socket: socket.id
    }
    redisPublish.publish(prefix + 'connected', JSON.stringify(message));

    socket.on('users.add', function (message) {
        io.emit('users.add', message);
    });
    socket.on('users.update', function (message) {
        io.emit('users.update', message);
    });
    socket.on('users.update', function (message) {
        io.emit('users.update', message);
    });
    socket.on('users.delete', function (message) {
        io.emit('users.delete', message);
    });
    socket.on('disconnect', () => {
        message = {
            socket: socket.id
        };
        redisPublish.publish(prefix + 'disconnected', JSON.stringify(message));
    });

});

server.listen(process.env.WEBSOCKET_PORT, function () {
    console.log(`Listening on port ${process.env.WEBSOCKET_PORT}`);
    console.log(`http://localhost:${process.env.WEBSOCKET_PORT}`);
});
