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
            "toss_winner":'',
            "first_innings":'',
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
        tossWinnerIndex:'',
        batsmans:'',
        fielders:''

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
                    mainthis.batsmans=mainthis.setBatmans();
                    mainthis.fielders=mainthis.setFielders();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        insertTossData:function(){
          var mainthis=this;
            if(this.match_data.toss_winner!=null && this.match_data.first_innings!=null){
                axios.post('/getmatchdata/match/settoss/'+this.match_data.match_id,{
                  toss_winner:this.match_data.toss_winner,
                  first_team:this.match_data.first_innings
                })
                .then(function(response){
                    mainthis.getMatchData();
                    console.log(response.data);
                })
                .catch(function(error){
                    console.log(error);
                });
            }
        },
        getTossWinner:function(){
          for(var i=0;i<this.match_data.teams.length;i++){
            if(this.match_data.teams[i].team_id==this.match_data.toss_winner){
              return i;
            }
          }
        },
        setBatmans:function(){
          var i=this.getTossWinner();
          if(this.match_data.first_innings=='bat'){
              return this.match_data.teams[i].players;
          }
          else if(this.match_data.first_innings=='bowl'){
            return this.match_data.teams[Math.abs(i-1)].players;
          }
        },
        setFielders:function(){
          var i=this.getTossWinner();
          if(this.match_data.first_innings=='bowl'){
              return this.match_data.teams[i].players;;
          }
          else if(this.match_data.first_innings=='bat'){
            return this.match_data.teams[Math.abs(i-1)].players;
          }
        },
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
          var toss_winner=this.getTossWinner();
          console.log(toss_winner);
          if(typeof toss_winner!='undefined'){
            return this.match_data.teams[toss_winner].team_name;
          }
          else{
            return 'No Team';
          }
       },

    }
});