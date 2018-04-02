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
        isSecInn: false,
        ball_data: {
            "current_over": 0,
            "current_ball": 0,
            "ball_run": 0,
            "incident": null,
            "extra_type": null,
            "who_out": 1,
            "clear_run": ''
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
    created: function () {
        this.match_id = this.getMatchID();
        this.getMatchData();
    },
    methods: {
        insertTossData: function () {
            var mainthis = this;
            if (this.match_data.toss_winner != null && this.match_data.first_innings != null) {
                axios.post('/getmatchdata/match/settoss/' + this.match_data.match_id, {
                    toss_winner: this.match_data.toss_winner,
                    first_team: this.match_data.first_innings
                }).then(function (response) {
                    mainthis.getMatchData();
                    console.log(response.data);
                }).catch(function (error) {
                    console.log(error);
                });
                mainthis.initInnings();
            }
        },
        initInnings: function () {
            axios.post('/getmatchdata/match/setinnings/' + this.match_data.match_id, {
                match_id: this.match_data.match_id
            }).then(function (response) {
                console.log(response.data);
            }).catch(function (error) {
                console.log(error);
            });
        },
        swapStrike: function () {
            var x;
            x = this.on_strike.id;
            this.on_strike.id = this.non_strike.id;
            this.non_strike.id = x;
        },
        setBowler: function (x) {
            this.bowler = x;
        },
        addNewBall: function (event) {
            var mainthis = this;
            if (this.match_data.toss_winner != null && this.match_data.first_innings != null) {
                this.total_run += this.ball_data.ball_run;
                this.fillExtraRunArray();
                this.fillLastTenArray();
                this.countPartnership();
                var ball = this.ball_data.current_over + '.' + this.ball_data.current_ball;
                if (ball <= this.match_data.over) {
                    if (mainthis.ball_data.incident != null) {
                        mainthis.checkWhoOut(event);
                    }
                    axios.post('/getmatchdata/match/addnewball/' + this.match_data.match_id, {
                        player_bat: mainthis.on_strike.id,
                        non_strike: mainthis.non_strike.id,
                        player_bowl: mainthis.bowler,
                        ball_number: mainthis.ball_data.current_over + '.' + mainthis.ball_data.current_ball,
                        incident: mainthis.ball_data.incident,
                        run: mainthis.ball_data.ball_run,
                        extra_type: mainthis.ball_data.extra_type,
                        who_out: mainthis.ball_data.who_out
                    }).then(function (response) {
                        mainthis.applySwap();
                        mainthis.takeWicket(event);
                        if (mainthis.ball_data.current_ball == 0 && (mainthis.ball_data.extra_type == null || mainthis.ball_data.extra_type == 'by')) {
                            mainthis.bowler = null;
                        }
                        if (ball == mainthis.match_data.over || mainthis.calcFirstInningsWicket() >= (mainthis.match_data.player_total - 1) || (mainthis.total_run > mainthis.first_innings.total_first && mainthis.isSecInn) ) {
                            mainthis.on_strike.id = null;
                            mainthis.non_strike.id = null;
                            mainthis.bowler = null;
                            mainthis.inningsEnd = true;
                        }
                        console.log(response.data);
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (ball > this.match_data.over || mainthis.calcFirstInningsWicket() >= (mainthis.match_data.player_total - 1) || (mainthis.total_run > mainthis.first_innings.total_first && mainthis.isSecInn)) {
                    this.on_strike.id = null;
                    this.non_strike.id = null;
                    this.bowler = null;
                    this.inningsEnd = true;
                }
            }

        },
        checkWhoOut: function () {
            var striker_id = event.srcElement.id;
            if (striker_id == this.non_strike.id) {
                this.ball_data.who_out = 0;
            }
            else {
                this.ball_data.who_out = 1;
            }
        },
        prepareNextBall: function (run, local_extra_type, ball_incident) {
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
                this.old_bowler = this.bowler;
            }
        },
        setBallRun: function (run, local_extra_type, ball_incident, event) {
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
            this.ball_data.clear_run = run;
            this.addNewBall(event);
            if (local_extra_type != null) {
                this.isExtraBall = false;
            }
        },
        incBall: function (x) {
            this.ball_consumed[this.calculateBall(x)].ball += 1;
        },
        incRun: function (x, y) {
            this.ball_consumed[this.calculateBall(x)].run += y;
        },
        runConditions: function (x, run) {
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
        fillExtraRunArray: function () {
            if (this.ball_data.extra_type != null) {
                var ex_data = {};
                ex_data['extra'] = this.ball_data.ball_run;
                ex_data['type'] = this.ball_data.extra_type;
                if (this.ball_data.extra_type == 'nb') {
                    ex_data['extra'] = '';
                } else if (this.ball_data.extra_type == 'wd') {
                    ex_data['extra'] -= 1;
                }
                this.extra_runs.push(ex_data);
            }
        },
        fillLastTenArray: function () {
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
        takeWicket: function (event) {
            if (this.ball_data.incident != null && (this.ball_data.incident == 'b' || this.ball_data.incident == 'c' || this.ball_data.incident == 'lbw' || this.ball_data.incident == 'ro')) {
                var striker_id = event.srcElement.id;
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
        strikeBat: function (x) {
            if (this.non_strike.id != this.on_strike.id && this.non_strike.id == x) {
                this.swapStrike();
            } else if (x != this.on_strike.id && this.on_strike.id == '') {
                this.on_strike.id = x;
            } else if (x != this.on_strike.id) {
                this.on_strike.id = x;
            }
        },
        nonStrikeBat: function (x) {
            if (this.on_strike.id != this.non_strike.id && this.on_strike.id == x) {
                this.swapStrike();
            } else if (x != this.non_strike.id && this.non_strike.id == '') {
                this.non_strike.id = x;
            } else if (x != this.non_strike.id) {
                this.non_strike.id = x;
            }
        },
        countPartnership: function () {
            this.partnership.run += this.ball_data.ball_run;
            if (this.ball_data.extra_type != 'wd') {
                this.partnership.ball += 1;
            }
        },
        endInnings: function () {
            var mainthis = this;
            axios.post('/getmatchdata/match/endinnings/' + this.match_data.match_id, {
                match_id: this.match_data.match_id
            }).then(function (response) {
                console.log(response.data);
                mainthis.initInnings();
                if (!mainthis.isSecInn) {
                    mainthis.prepareSecInnings();
                }
                else {
                    mainthis.winner.matchEnded = true;
                }
            }).catch(function (error) {
                console.log(error);
            });
        },
        getFirstBat: function () {
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
        applySwap: function () {
            if (this.ball_data.clear_run % 2 == 1) {
                this.swapStrike();
            }
            if (this.ball_data.current_ball == 0 && (this.ball_data.extra_type == null || this.ball_data.extra_type == 'by')) {
                this.swapStrike();
            }
        },
        undoLastBall: function () {
            var mainthis = this;
            axios.post('/getmatchdata/match/deletelast/' + this.match_data.match_id, {
                match_id: mainthis.match_id
            }).then(function (response) {
                mainthis.resumeMatch();
                console.log(response.data);
            }).catch(function (error) {
                console.log(error);
            });
        }
    }
});