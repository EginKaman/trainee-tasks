import {createServer} from "http";
import {Server} from "socket.io"
import {authorize} from 'socketio-jwt';

import app from "express";

var server = createServer(app)
var io = new Server(server);
require('dotenv').config({path: '../.env'});

server.listen(3000);
io.use(
    authorize({
        secret: process.env.JWT_SECRET,
        algorithms: [process.env.JWT_ALGO]
    })
)
io.on('connection', function (socket) {
    socket.on('users.add', function (message) {
        io.emit('users.add', message);
    });
    socket.on('users.update', function (message) {
        io.emit('users.add', message);
    });
    socket.on('users.delete', function (message) {
        io.emit('users.add', message);
    });
    socket.on('disconnect', function () {
        socket.broadcast.emit('users.delete', {

        })
    })
});
