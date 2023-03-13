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
    })
)
io.on('connection', function (socket) {
    socket.on('updates.add', function (message) {
        io.emit('updates.add', message);
    });
    socket.on('disconnect', function () {
        io.emit('updates.add', 'User has disconnected.');
    })
});
