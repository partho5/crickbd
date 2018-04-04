Vue.mixin({
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
                    if(response.data==""){
                        window.location.replace("/");
                    }
                    else{
                        mainthis.match_data = response.data;
                        mainthis.setBatmans();
                        mainthis.setFielders();
                        mainthis.ball_consumed = [];
                        mainthis.createBallConsumedArray();
                        mainthis.resumeMatch();
                    }
                }).catch(function (error) {
                console.log(error);
            });
        },
        setBatmans: function () {
            var i = this.getTossWinner();
            if (!this.isSecInn) {
                if (this.match_data.first_innings == 'bat') {
                    this.batsmans = this.match_data.teams[i].players;
                } else if (this.match_data.first_innings == 'bowl') {
                    this.batsmans = this.match_data.teams[Math.abs(i - 1)].players;
                }
            } else if (this.isSecInn) {
                if (this.match_data.first_innings == 'bat') {
                    this.fielders = this.match_data.teams[i].players;
                } else if (this.match_data.first_innings == 'bowl') {
                    this.fielders = this.match_data.teams[Math.abs(i - 1)].players;
                }
            }
        },
        setFielders: function () {
            var i = this.getTossWinner();
            if (!this.isSecInn) {
                if (this.match_data.first_innings == 'bowl') {
                    this.fielders = this.match_data.teams[i].players;
                } else if (this.match_data.first_innings == 'bat') {
                    this.fielders = this.match_data.teams[Math.abs(i - 1)].players;
                }
            } else if (this.isSecInn) {
                if (this.match_data.first_innings == 'bowl') {
                    this.batsmans = this.match_data.teams[i].players;
                } else if (this.match_data.first_innings == 'bat') {
                    this.batsmans = this.match_data.teams[Math.abs(i - 1)].players;
                }
            }
        },
        createBallConsumedArray: function () {
            var mainthis = this;
            this.ball_consumed = [];
            for (var i = 0; i < mainthis.fielders.length; i++) {
                var obj = {};
                obj['id'] = mainthis.fielders[i].player_id;
                obj['ball'] = 0;
                obj['run'] = 0;
                obj['out'] = null;
                obj['w_taker'] = null;
                mainthis.ball_consumed.push(obj);
            }
            for (var i = 0; i < mainthis.batsmans.length; i++) {
                var obj = {};
                obj['id'] = mainthis.batsmans[i].player_id;
                obj['ball'] = 0;
                obj['run'] = 0;
                obj['out'] = null;
                obj['w_taker'] = null;
                mainthis.ball_consumed.push(obj);
            }
        },
        resumeMatch: function () {
            var mainthis = this;
            axios.get('/getresumematchdata/' + mainthis.match_id)
                .then(function (response) {
                    console.log(response.data);
                    mainthis.setResumeBasic(response.data);
                }).catch(function (error) {
                console.log(error);
            });
        },
        getTossWinner: function () {
            for (var i = 0; i < this.match_data.teams.length; i++) {
                if (this.match_data.teams[i].team_id == this.match_data.toss_winner) {
                    return i;
                }
            }
        },
        getPlayerName: function (x) {
            for (var i = 0; i < this.match_data.teams.length; i++) {
                for (j = 0; j < this.match_data.teams[i].players.length; j++) {
                    if (this.match_data.teams[i].players[j].player_id == x) {
                        return this.match_data.teams[i].players[j].player_name;
                        break;
                    }
                }
            }
        },
        alreadyOut: function (x) {
            if (this.ball_consumed[x].out == null) {
                return true;
            } else {
                return false;
            }
        },
        calcFirstInningsWicket: function () {
            var wicket = 0;
            for (var i = 0; i < this.ball_consumed.length; i++) {
                if (this.ball_consumed[i].out != null) {
                    wicket++;
                }
            }
            return wicket;
        },
        calculateBall: function (x) {
            for (var i = 0; i < this.ball_consumed.length; i++) {
                if (parseInt(x) == parseInt(this.ball_consumed[i].id)) {
                    return i;
                    break;
                }
            }
        },
        prepareSecInnings: function () {
            this.isSecInn = true;
            this.first_innings.total_first = this.total_run;
            this.total_run = 0;
            this.first_innings.first_inn_wicket = this.calcFirstInningsWicket();
            this.first_innings.first_inn_over = this.ball_data.current_over + '.' + this.ball_data.current_ball;
            this.ball_data.current_ball = 0;
            this.ball_data.current_over = 0;
            this.ball_data.ball_run = 0;
            this.ball_data.extra_type = null;
            this.ball_data.incident = null;
            this.last_ten = [];
            this.extra_runs = [];
            this.inningsEnd = false;
            this.partnership.ball = 0;
            this.partnership.run = 0;
            this.setBatmans();
            this.setFielders();
            this.createBallConsumedArray();
        },
        setResumeBasic: function (resume_data) {
            var x = resume_data[1];
            if (resume_data[0].stage != 'before_match_start' && resume_data[0].stage != 'first_innings_ended' && resume_data[0].stage != 'match_ended') {
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
                this.old_bowler = x[12];
                this.first_innings.total_first = x[6]['total_first'];
                this.first_innings.first_inn_wicket = x[6]['first_inn_wicket'];
                this.first_innings.first_inn_over = x[6]['first_inn_over'];
                this.extra_runs = x[5];
                this.ball_data.ball_run = x[11]['ball_run'];
                this.ball_data.incident = x[11]['incident'];
                this.ball_data.extra_type = x[11]['extra_type'];
                this.ball_data.who_out = x[11]['who_out'];
            } else if (resume_data[0].stage == 'first_innings_ended') {
                this.isSecInn = true;
                this.prepareSecInnings();
                this.first_innings.total_first = x[6]['total_first'];
                this.first_innings.first_inn_wicket = x[6]['first_inn_wicket'];
                this.first_innings.first_inn_over = x[6]['first_inn_over'];
            } else if (resume_data[0].stage == 'match_ended') {
                this.isSecInn = true;
                this.winner.matchEnded = true;
                this.first_innings.total_first = x[0].run;
                this.first_innings.first_inn_wicket = x[0].wicket;
                this.first_innings.first_inn_over = x[0].over;
                for (var i = x[1].over.length - 1; i >= 0; i--) {
                    if (x[1].over[i] == '.') {
                        this.ball_data.current_ball = parseInt(x[1].over.slice(i + 1));
                        this.ball_data.current_over = parseInt(x[1].over.slice(0, i));
                        break;
                    }
                }
                this.total_run = x[1].run;
                this.ball_consumed = x[2];
            }
        },
        countBowlerWicket: function (x) {
            var wicket = 0;
            this.ball_consumed.forEach(function (item, index) {
                if (item.w_taker == x) {
                    wicket++;
                }
            });
            return wicket;
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
    },
    filters: {
        convertOver: function (x) {
            var over = 0;
            var bowl = 0;
            if (x) {
                over = parseInt(eval(x / 6));
                bowl = eval(x % 6);
                return over + '.' + bowl;
            } else {
                return x;
            }
        },
        localDateTime:function(x){
            var d=new Date(x);
            return d.toDateString();
        }
    },
    computed: {
        countWicket: function () {
            return this.calcFirstInningsWicket();
        },
        calcRemainingBall: function () {
            return ((this.match_data.over * 6) - ((this.ball_data.current_over * 6) + this.ball_data.current_ball));
        },
        calcRemainingRun: function () {
            var x = this.first_innings.total_first - this.total_run + 1;
            return x >= 0 ? x : 0;
        },
        totalExtra: function () {
            var total = 0;
            for (var i = 0; i < this.extra_runs.length; i++) {
                if (this.extra_runs[i].type == 'nb' || this.extra_runs[i].type == 'wd') {
                    total++;
                }
                total += this.extra_runs[i].extra;
            }
            return total;
        },
        battingTeam: function () {
            var i = this.getTossWinner();
            var team = null;
            if (!this.isSecInn) {
                if (this.match_data.first_innings == 'bat') {
                    team = this.match_data.teams[i].team_name;
                } else if (this.match_data.first_innings == 'bowl') {
                    team = this.match_data.teams[Math.abs(i - 1)].team_name;
                }
            } else if (this.isSecInn) {
                if (this.match_data.first_innings == 'bat') {
                    team = this.match_data.teams[Math.abs(i - 1)].team_name;
                } else if (this.match_data.first_innings == 'bowl') {
                    team = this.match_data.teams[i].team_name;
                }
            }
            return team;
        },
        fieldingTeam: function () {
            var i = this.getTossWinner();
            var team = null;
            if (!this.isSecInn) {
                if (this.match_data.first_innings == 'bowl') {
                    team = this.match_data.teams[i].team_name;
                } else if (this.match_data.first_innings == 'bat') {
                    team = this.match_data.teams[Math.abs(i - 1)].team_name;
                }
            } else if (this.isSecInn) {
                if (this.match_data.first_innings == 'bowl') {
                    team = this.match_data.teams[Math.abs(i - 1)].team_name;
                } else if (this.match_data.first_innings == 'bat') {
                    team = this.match_data.teams[i].team_name;
                }
            }
            return team;
        },
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
        decideWinner: function () {
            var overs = this.ball_data.current_over + '.' + this.ball_data.current_ball;
            var batt = this.getFirstBat();
            if (this.isSecInn && this.winner.matchEnded && (this.countWicket >= (this.match_data.player_total - 1) || overs >= this.match_data.over || this.total_run > this.first_innings.total_first)) {
                this.winner.matchEnded = true;
                if (this.first_innings.total_first == this.total_run && overs == this.match_data.over) {
                    this.winner.isDrawn = true;
                    this.winner.matchEnded = true;
                } else if (this.first_innings.total_first > this.total_run && (overs >= this.match_data.over || this.countWicket >= (this.match_data.player_total - 1))) {
                    this.winner.winning_team_id = batt[0];
                    this.winner.matchEnded = true;
                    this.winner.win_by = "run";
                    this.winner.win_digit = eval(this.first_innings.total_first - this.total_run);
                    this.winner.winning_team_name = this.fieldingTeam;
                } else {
                    this.winner.winning_team_id = batt[1];
                    this.winner.matchEnded = true;
                    this.winner.win_by = "wicket";
                    this.winner.win_digit = eval(this.match_data.player_total - 1 - this.countWicket);
                    this.winner.winning_team_name = this.battingTeam;
                }
                return true;
            }
            else {
                return false;
            }
        },
    }
})
;