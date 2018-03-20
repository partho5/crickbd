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
            "toss_winner":null,
            "first_innings":null,
            "start_time":"",
            "created_at":"",
            "updated_at":"",
            "teams":[
                {"team_id":'',"team_name":"","match_id":'','players':[]},
                {"team_id":'',"team_name":"","match_id":'','players':[]}
                ]
       },
        on_strike:'',
        bowler:'',
        ask_start:false,

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
        },
        insertTossData:function(){
            axios.post('/getmatchdata/match/settoss',{
                match_id:this.match_data.match_id,
                toss_winner:this.match_data.toss_winner,
                first_team:this.match_data.first_innings
                })
                .then(function(response){
                    console.log(response);
                })
                .catch(function(error){
                    console.log(error);
                });
        }
    },
    computed:{
       checkToss:function(){
           if(this.match_data.first_innings!=null){
               return true;
           }
           else{
               return false;
           }
       },
       tossWinnerTeam:function(){
          for(var i=0;i<this.match_data.teams.length;i++){
            if(this.match_data.teams[i].team_id==this.match_data.toss_winner){
              return this.match_data.teams[i].team_name;
            }
          }
       },

    }
});