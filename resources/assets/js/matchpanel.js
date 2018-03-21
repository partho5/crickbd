require('./bootstrap');

var matchpanel = new Vue({
    el: '#match-panel',
    data: {
        match_id: '',
        isExtraBall: false,
        match_data: {
            "match_id": '',
            "user_id": '',
            "over": '',
            "location": "",
            "player_total": '',
            "toss_winner": '',
            "first_innings": '',
            "start_time": "",
            "created_at": "",
            "updated_at": "",
            "teams": [
                {"team_id": '', "team_name": "", "match_id": '', 'players': []},
                {"team_id": '', "team_name": "", "match_id": '', 'players': []}
            ]
        },
        on_strike: '',
        non_strike: '',
        bowler: '',
        ask_start: false,
        tossWinnerIndex: '',
        batsmans: '',
        fielders: '',
        batting_team: '',
        fielding_team: '',
        isSecInn: true,
        ball_data: {
            "current_over": 0,
            "current_ball": 0,
            "ball_run": 0,
            "incident": null,
            "extra_type":null,
        },

    },
    created: function () {
        this.match_id = this.getMatchID();
        this.getMatchData();
    },
    methods: {
        getMatchID: function () {
            var url = window.location.href;
            for (var i = url.length - 1; i >= 0; i--) {
                if (url[i] == '/') {
                    break;
                }
            }
            return Number(url.slice(i + 1));
        },
        getMatchData: function () {
            var mainthis = this;
            axios.get('/getmatchdata/' + mainthis.match_id)
                .then(function (response) {
                    mainthis.match_data = response.data;
                    mainthis.batsmans = mainthis.setBatmans();
                    mainthis.fielders = mainthis.setFielders();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        insertTossData: function () {
            var mainthis = this;
            if (this.match_data.toss_winner != null && this.match_data.first_innings != null) {
                axios.post('/getmatchdata/match/settoss/' + this.match_data.match_id, {
                    toss_winner: this.match_data.toss_winner,
                    first_team: this.match_data.first_innings
                })
                    .then(function (response) {
                        mainthis.getMatchData();
                        console.log(response.data);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });

                axios.post('/getmatchdata/match/setinnings/' + this.match_data.match_id, {
                    match_id: this.match_data.match_id
                })
                    .then(function (response) {
                        console.log(response.data);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        },
        getTossWinner: function () {
            for (var i = 0; i < this.match_data.teams.length; i++) {
                if (this.match_data.teams[i].team_id == this.match_data.toss_winner) {
                    return i;
                }
            }
        },
        setBatmans: function () {
            var i = this.getTossWinner();
            if (this.match_data.first_innings == 'bat') {
                this.batting_team = this.match_data.teams[i].team_name;
                return this.match_data.teams[i].players;
            } else if (this.match_data.first_innings == 'bowl') {
                this.batting_team = this.match_data.teams[Math.abs(i - 1)].team_name;
                return this.match_data.teams[Math.abs(i - 1)].players;
            }
        },
        setFielders: function () {
            var i = this.getTossWinner();
            if (this.match_data.first_innings == 'bowl') {
                this.fielding_team = this.match_data.teams[i].team_name;
                return this.match_data.teams[i].players;
                ;
            } else if (this.match_data.first_innings == 'bat') {
                this.fielding_team = this.match_data.teams[i].team_name;
                return this.match_data.teams[Math.abs(i - 1)].players;
            }
        },
        strikeBat: function (x) {
            if (this.non_strike != this.on_strike && this.non_strike == x) {
                this.swapStrike();
            } else if (x != this.on_strike && this.on_strike == '') {
                this.on_strike = x;
            } else if (x != this.on_strike) {
                this.on_strike = x;
            }
        },
        nonStrikeBat: function (x) {
            if (this.on_strike != this.non_strike && this.on_strike == x) {
                this.swapStrike();
            } else if (x != this.non_strike && this.non_strike == '') {
                this.non_strike = x;
            } else if (x != this.non_strike) {
                this.non_strike = x;
            }
        },
        swapStrike: function () {
            var x;
            x = this.on_strike;
            this.on_strike = this.non_strike;
            this.non_strike = x;
        },
        setBowler: function (x) {
            this.bowler = x;
        },
        addNewBall: function () {
            var mainthis = this;
            if (this.match_data.toss_winner != null && this.match_data.first_innings != null) {
                axios.post('/getmatchdata/match/addnewball/' + this.match_data.match_id, {
                    player_bat: mainthis.on_strike,
                    player_bowl: mainthis.non_strike,
                    ball_number: mainthis.ball_data.current_over + '.' + mainthis.ball_data.current_ball,
                    incident: mainthis.ball_data.incident,
                    run: mainthis.ball_data.ball_run,
                    extra_type:mainthis.ball_data.extra_type
                })
                    .then(function (response) {
                        console.log(response.data);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        },
        prepareNextBall: function () {
            if ((this.ball_data.current_ball + 1) <= 5) {
                this.ball_data.current_ball += 1;
            } else if ((this.ball_data.current_ball + 1) == 6) {
                this.ball_data.current_ball = 0;
                this.ball_data.current_over += 1;
                this.swapStrike();
            }
        },
        setBallRun: function (run,local_extra_type,ball_incident) {
            this.ball_data.incident=ball_incident;
            this.ball_data.extra_type=local_extra_type;
            if(local_extra_type==null){
                this.ball_data.ball_run=run;
                this.prepareNextBall();
            }
            else{
                this.ball_data.ball_run=run+1;
            }
            if(run%2==1){
                this.swapStrike();
            }
            this.addNewBall();
        },

    },
    computed: {
        checkToss: function () {
            if (this.match_data.first_innings != null) {
                return true;
            } else {
                return false;
            }
        },
        tossWinnerTeam: function () {
            var toss_winner = this.getTossWinner();
            if (typeof toss_winner != 'undefined') {
                return this.match_data.teams[toss_winner].team_name;
            } else {
                return 'No Team';
            }
        },

    }
});