var app = require('express')();

var server = require('http').Server(app);

var io = require('socket.io')(server);


server.listen(3000);


app.get('/', function(request, response) {
	response.send('Hello!');
});

io.on('connection', function(socket) {

	socket.on('chat', function(payload) {
		console.log(payload);
		io.emit(payload[0], payload);
	});


	// console.log('Boom! A connection was made!');
});