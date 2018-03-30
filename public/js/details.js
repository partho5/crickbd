var socket = io('http://127.0.0.1:3000');

var details = new Vue({
    el: '#detail',
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
            "teams": [{
                "team_id": '',
                "team_name": "",
                "match_id": '',
                'players': []
            }, {
                "team_id": '',
                "team_name": "",
                "match_id": '',
                'players': []
            }]
        },
        on_strike: {
            id: '',
        },
        non_strike: {
            id: '',
        },
        ball_consumed: [],
        bowler: '',
        old_bowler: null,
        ask_start: false,
        tossWinnerIndex: '',
        batsmans: '',
        fielders: '',
        batting_team: '',
        fielding_team: '',
        isSecInn: false,
        ball_data: {
            "current_over": 0,
            "current_ball": 0,
            "ball_run": 0,
            "incident": null,
            "extra_type": null,
            "who_out": 1
        },
        total_run: 0,
        last_ten: [],
        extra_runs: [],
        first_innings: {
            "total_first": 0,
            "first_inn_wicket": 0,
            "first_inn_over": 0
        },
        inningsEnd: false,
        partnership: {
            "ball": 0,
            "run": 0
        },
        winner: {
            "matchEnded": false,
            "winning_team_id": null,
            "winning_team_name": '',
            "win_digit": null,
            "win_by": null,
            "isDrawn": false
        }
    },
    created: function() {
        this.match_id = this.getMatchID();
        this.getMatchData();
        socket.on("match-details:" + this.match_id, function(data) {
            this.setResumeBasic(data);
            console.log(data);
        }.bind(this));
    },
    methods: {
        setResumeBasic: function(x) {
            if (x.length > 1) {
                if (x[3]) {
                    this.prepareSecInnings();
                }
                this.total_run = parseInt(x[0][0]['total_run']);
                this.ball_consumed = x[2];
                this.last_ten = x[4];
                this.partnership.ball = parseInt(x[10]['ball']);
                this.partnership.run = parseInt(x[10]['run']);
                for (var i = x[1][0]['overs'].length - 1; i >= 0; i--) {
                    if (x[1][0]['overs'][i] == '.') {
                        this.ball_data.current_ball = parseInt(x[1][0]['overs'].slice(i + 1));
                        this.ball_data.current_over = parseInt(x[1][0]['overs'].slice(0, i));
                        break;
                    }
                }
                this.isSecInn = x[3];
                this.on_strike.id = x[7]['id'];
                this.non_strike.id = x[8]['id'];
                this.bowler = x[9];
                this.first_innings.total_first = x[6]['total_first'];
                this.first_innings.first_inn_wicket = x[6]['first_inn_wicket'];
                this.first_innings.first_inn_over = x[6]['first_inn_over'];
                this.extra_runs = x[5];
                this.ball_data.ball_run = x[11]['ball_run'];
                this.ball_data.incident = x[11]['incident'];
                this.ball_data.extra_type = x[11]['extra_type'];
                this.ball_data.who_out = x[11]['who_out'];
            } else {
                this.isSecInn = true;
                this.first_innings.total_first = x[0]['total_first'];
                this.first_innings.first_inn_wicket = x[0]['first_inn_wicket'];
                this.first_innings.first_inn_over = x[0]['first_inn_over'];
            }
        },
    }

});