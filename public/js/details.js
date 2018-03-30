var socket=io('http://127.0.0.1:3000');

var details=new Vue({
	el:'#detail',
	data:{
		match_id:''
	},
	created:function(){
		this.match_id=this.getMatchID();
		socket.on("match-details:"+this.match_id,function(data){
			console.log(data);
		}.bind(this));
	},

});