require('./bootstrap');

var matchpanel=new Vue({
   el:'#match-panel',
    data:{
       match_id:'',
       isExtraBall:false,
        match_data:{
           "match_id":'',
            "user_id":'',
            "over":'',
            "location":"",
            "player_total":'',
            "start_time":"",
            "created_at":"",
            "updated_at":"",
            "teams":[
                {"team_id":'',"team_name":"","match_id":''},
                {"team_id":'',"team_name":"","match_id":''}
                ]
       }
    },
    created:function(){
       this.match_id=this.getMatchID();
        this.getMatchData();
    },
    methods:{
       getMatchID:function(){
           var url=window.location.href;
           for(var i=url.length-1;i>=0;i--){
               if(url[i]=='/'){
                   break;
               }
           }
           return Number(url.slice(i+1));
       },
        getMatchData:function(){
           var mainthis=this;
            axios.get('/getmatchdata/'+mainthis.match_id)
                .then(function (response) {
                    mainthis.match_data=response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    }
});