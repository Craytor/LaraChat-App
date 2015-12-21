var app = require('express')();
var server = require('http').Server(app);

var io = require('socket.io')(server);
var users = names = {};

server.listen(3000);


app.get('/', function(request, response) {
	response.send('Hello!');
});

console.log('LaraChat Node.js server has booted...');

io.on('connection', function(socket) {

	socket.on('join', function(user) {
		var d = new Date();
		var time = d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();

		console.info('[' + time + '] ' + user.name + ' has connected to ' + user.channel);

		socket.userId 	= user.id;
		socket.userName = user.name;

		users[user.id] = socket;

		names[user.id] = {
			'name': user.name,
			'socketId': socket.id,
		};

		// console.log(user.channel);
		// console.log(user.name);
		// console.log(user.id);

		function updateNames() {
			io.sockets.emit(user.channel + '.users', names);
		}


		updateNames();


		socket.on('chat', function(payload) {
			console.log(payload);
			io.emit('chat.' + payload[0], payload);
		});

		socket.on('disconnect', function() {
			if(!socket.name) return;

			delete users[user.id];
			delete names[user.id];

			updateNames();

			console.info('[' + time + '] ' + user.name + ' has disconnected from ' + user.channel);
		});


	});

	// console.log('Boom! A connection was made!');
});