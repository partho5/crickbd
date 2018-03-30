var server=require('http').Server();

var io=require('socket.io')(server);
var Redis=require('ioredis');
var redis=new Redis();
redis.subscribe('match-details');
redis.on('message',function (channel, message) {
	message=JSON.parse(message);
    io.emit(channel+':'+message.data.match_id,message.data.resume_data);
});

server.listen(3000);

