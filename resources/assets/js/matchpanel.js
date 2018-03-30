require('./bootstrap');
require('./basicMixin');

var matchpanel = new Vue({
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
    el: '#match-panel',
    created: function() {
        this.match_id = this.getMatchID();
        this.getMatchData();
    },
    methods: {
        insertTossData: function() {
            var mainthis = this;
            if (this.match_data.toss_winner != null && this.match_data.first_innings != null) {
                axios.post('/getmatchdata/match/settoss/' + this.match_data.match_id, {
                    toss_winner: this.match_data.toss_winner,
                    first_team: this.match_data.first_innings
                }).then(function(response) {
                    mainthis.getMatchData();
                    console.log(response.data);
                }).catch(function(error) {
                    console.log(error);
                });
                mainthis.initInnings();
            }
        },
        initInnings: function() {
            axios.post('/getmatchdata/match/setinnings/' + this.match_data.match_id, {
                match_id: this.match_data.match_id
            }).then(function(response) {
                console.log(response.data);
            }).catch(function(error) {
                console.log(error);
            });
        },
        strikeBat: function(x) {
            if (this.non_strike.id != this.on_strike.id && this.non_strike.id == x) {
                this.swapStrike();
            } else if (x != this.on_strike.id && this.on_strike.id == '') {
                this.on_strike.id = x;
            } else if (x != this.on_strike.id) {
                this.on_strike.id = x;
            }
        },
        nonStrikeBat: function(x) {
            if (this.on_strike.id != this.non_strike.id && this.on_strike.id == x) {
                this.swapStrike();
            } else if (x != this.non_strike.id && this.non_strike.id == '') {
                this.non_strike.id = x;
            } else if (x != this.non_strike.id) {
                this.non_strike.id = x;
            }
        },
        swapStrike: function() {
            var x;
            x = this.on_strike.id;
            this.on_strike.id = this.non_strike.id;
            this.non_strike.id = x;
        },
        setBowler: function(x) {
            this.bowler = x;
        },
        addNewBall: function(event) {
            var mainthis = this;
            if (this.match_data.toss_winner != null && this.match_data.first_innings != null) {
                this.total_run += this.ball_data.ball_run;
                this.fillExtraRunArray();
                this.fillLastTenArray();
                this.countPartnership();
                var ball = this.ball_data.current_over + '.' + this.ball_data.current_ball;
                if (ball <= this.match_data.over) {
                    axios.post('/getmatchdata/match/addnewball/' + this.match_data.match_id, {
                        player_bat: mainthis.on_strike.id,
                        non_strike: mainthis.non_strike.id,
                        player_bowl: mainthis.bowler,
                        ball_number: mainthis.ball_data.current_over + '.' + mainthis.ball_data.current_ball,
                        incident: mainthis.ball_data.incident,
                        run: mainthis.ball_data.ball_run,
                        extra_type: mainthis.ball_data.extra_type,
                        who_out: mainthis.ball_data.who_out
                    }).then(function(response) {
                        mainthis.takeWicket(event);
                        if (mainthis.ball_data.current_ball == 0) {
                            mainthis.bowler = '';
                        }
                        if (ball == mainthis.match_data.over || mainthis.calcFirstInningsWicket() >= (mainthis.match_data.player_total - 1)) {
                            mainthis.on_strike.id = null;
                            mainthis.non_strike.id = null;
                            mainthis.bowler = null;
                            mainthis.inningsEnd = true;
                        }
                        console.log(response.data);
                    }).catch(function(error) {
                        console.log(error);
                    });
                } else if (ball > this.match_data.over || mainthis.calcFirstInningsWicket() >= (mainthis.match_data.player_total - 1)) {
                    this.on_strike.id = null;
                    this.non_strike.id = null;
                    this.bowler = null;
                    this.inningsEnd = true;
                }
            }

        },
        prepareNextBall: function(run, local_extra_type, ball_incident) {
            this.runConditions(local_extra_type, run);
            if ((this.ball_data.current_ball + 1) <= 5) {
                if (((local_extra_type == 'nb' || local_extra_type == 'wd') && run >= 1) || local_extra_type == null) {
                    this.incBall(this.on_strike.id);
                    this.incBall(this.bowler);
                } else if (local_extra_type == 'by') {
                    this.incBall(this.bowler);
                    this.incBall(this.on_strike.id);
                }
                this.ball_data.current_ball += 1;
            } else if ((this.ball_data.current_ball + 1) == 6) {
                this.ball_data.current_ball = 0;
                this.ball_data.current_over += 1;
                if (((local_extra_type == 'nb' || local_extra_type == 'wd') && run >= 1) || local_extra_type == null) {
                    this.incBall(this.on_strike.id);
                    this.incBall(this.bowler);
                } else if (local_extra_type == 'by') {
                    this.incBall(this.bowler);
                    this.incBall(this.on_strike.id);
                }
                this.swapStrike();
                this.old_bowler = this.bowler;
            }
        },
        setBallRun: function(run, local_extra_type, ball_incident, event) {
            this.ball_data.incident = ball_incident;
            this.ball_data.extra_type = local_extra_type;
            if (local_extra_type == null || local_extra_type == 'by') {
                this.ball_data.ball_run = run;
                this.prepareNextBall(run, local_extra_type, ball_incident);
            } else {
                this.runConditions(local_extra_type, run);
                if (local_extra_type == 'nb' && run >= 1) {
                    this.incBall(this.on_strike.id);
                }
                this.ball_data.ball_run = run + 1;
            }
            if (run % 2 == 1) {
                this.swapStrike();
            }
            this.addNewBall(event);
            if (local_extra_type != null) {
                this.isExtraBall = false;
            }
        },
        incBall: function(x) {
            this.ball_consumed[this.calculateBall(x)].ball += 1;
        },
        incRun: function(x, y) {
            this.ball_consumed[this.calculateBall(x)].run += y;
        },
        runConditions: function(x, run) {
            if (x == null) {
                this.incRun(this.bowler, run);
                this.incRun(this.on_strike.id, run);
            } else if (x == 'wd') {
                this.incRun(this.bowler, (run + 1));
            } else if (x == 'nb') {
                this.incRun(this.bowler, (run + 1));
                this.incRun(this.on_strike.id, run);
            } else if (x == 'by') {
                this.incRun(this.bowler, run);
            }
        },
        alreadyOut: function(x) {
            if (this.ball_consumed[x].out == null) {
                return true;
            } else {
                return false;
            }
        },
        getPlayerName: function(x) {
            for (var i = 0; i < this.match_data.teams.length; i++) {
                for (j = 0; j < this.match_data.teams[i].players.length; j++) {
                    if (this.match_data.teams[i].players[j].player_id == x) {
                        return this.match_data.teams[i].players[j].player_name;
                        break;
                    }
                }
            }
        },
        fillExtraRunArray: function() {
            if (this.ball_data.extra_type != null) {
                var ex_data = {};
                ex_data['extra'] = this.ball_data.ball_run;
                ex_data['type'] = this.ball_data.extra_type;
                if (this.ball_data.extra_type == 'nb') {
                    ex_data['extra'] = 1;
                }
                this.extra_runs.push(ex_data);
            }
        },
        fillLastTenArray: function() {
            var lastball = '';
            if (this.ball_data.extra_type == null) {
                lastball = this.ball_data.ball_run;
            } else {
                if (this.ball_data.extra_type == 'by') {
                    lastball = this.ball_data.ball_run;
                } else {
                    lastball = this.ball_data.ball_run - 1;
                }
                lastball += this.ball_data.extra_type;
            }
            if (this.ball_data.incident != null) {
                if (this.ball_data.ball_run == 0 || this.ball_data.ball_run == null || this.ball_data.incident == 'ro') {
                    lastball = 'W';
                } else {
                    lastball += 'W';
                }
            }
            if (this.last_ten.length >= 10) {
                this.last_ten.pop();
            }
            this.last_ten.unshift(lastball);
        },
        takeWicket: function(event) {
            if (this.ball_data.incident != null && (this.ball_data.incident == 'b' || this.ball_data.incident == 'c' || this.ball_data.incident == 'lbw' || this.ball_data.incident == 'ro')) {
                var striker_id = event.srcElement.id;
                if (striker_id == this.non_strike.id) {
                    this.ball_data.who_out = 0;
                }
                var striker = this.calculateBall(striker_id);
                this.ball_consumed[striker].out = this.ball_data.incident;
                this.ball_consumed[striker].w_taker = this.bowler;
                if (striker_id == this.on_strike.id) {
                    this.on_strike.id = null;
                } else if (striker_id == this.non_strike.id) {
                    this.non_strike.id = null;
                }
                this.partnership.ball = 0;
                this.partnership.run = 0;
            }
        },
        countPartnership: function() {
            this.partnership.run += this.ball_data.ball_run;
            if (this.ball_data.extra_type == null) {
                this.partnership.ball += 1;
            }
        },
        endInnings: function() {
            var mainthis = this;
            axios.post('/getmatchdata/match/endinnings/' + this.match_data.match_id, {
                match_id: this.match_data.match_id
            }).then(function(response) {
                console.log(response.data);
                mainthis.initInnings();
                mainthis.prepareSecInnings();
            }).catch(function(error) {
                console.log(error);
            });
        },
        getFirstBat: function() {
            var team1 = this.match_data.teams[0].team_id;
            var team2 = this.match_data.teams[1].team_id;
            if (this.match_data.first_innings == "bat") {
                if (team1 == this.match_data.toss_winner) {
                    return [team1, team2];
                } else {
                    return [team2, team1];
                }
            }
            if (this.match_data.first_innings == "bowl") {
                if (team1 == this.match_data.toss_winner) {
                    return [team2, team1];
                } else {
                    return [team1, team2];
                }
            }
        },
        decideWinner: function() {
            var overs = this.ball_data.current_over + '.' + this.ball_data.current_ball;
            var batt = this.getFirstBat();
            if (this.isSecInn && (this.countWicket >= (this.match_data.player_total - 1) || overs >= this.match_data.over)) {
                this.winner.matchEnded = true;
                if (this.first_innings.total_first == this.total_run && overs == this.match_data.over) {
                    this.winner.isDrawn = true;
                    this.winner.matchEnded = true;
                } else if (this.first_innings.total_first > this.total_run && (overs >= this.match_data.over || this.countWicket >= (this.match_data.player_total - 1))) {
                    this.winner.winning_team_id = batt[0];
                    this.winner.matchEnded = true;
                    this.winner.win_by = "run";
                    this.winner.win_digit = eval(this.first_innings.total_first - this.total_run);
                    this.winner.winning_team_name = this.fielding_team;
                } else {
                    this.winner.winning_team_id = batt[1];
                    this.winner.matchEnded = true;
                    this.winner.win_by = "wicket";
                    this.winner.win_digit = eval(this.match_data.player_total - 1 - this.countWicket);
                    this.winner.winning_team_name = this.batting_team;
                }
            }
        },
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
                this.endInnings();
                this.prepareSecInnings();
                this.first_innings.total_first = x[0]['total_first'];
                this.first_innings.first_inn_wicket = x[0]['first_inn_wicket'];
                this.first_innings.first_inn_over = x[0]['first_inn_over'];
            }
        },
    },
    computed: {
        checkToss: function() {
            if (this.match_data.first_innings != null) {
                return true;
            } else {
                return false;
            }
        },
        tossWinnerTeam: function() {
            var toss_winner = this.getTossWinner();
            if (typeof toss_winner != 'undefined') {
                return this.match_data.teams[toss_winner].team_name;
            } else {
                return 'No Team';
            }
        },
    }
});