Vue.mixin({
    methods: {
        getMatchID: function() {
            var url = window.location.href;
            for (var i = url.length - 1; i >= 0; i--) {
                if (url[i] == '/') {
                    break;
                }
            }
            return Number(url.slice(i + 1));
        },
        getMatchData: function() {
            var mainthis = this;
            axios.get('/getmatchdata/' + mainthis.match_id)
                .then(function(response) {
                    mainthis.match_data = response.data;
                    mainthis.setBatmans();
                    mainthis.setFielders();
                    mainthis.ball_consumed = [];
                    mainthis.createBallConsumedArray();
                    mainthis.resumeMatch();
                }).catch(function(error) {
                    console.log(error);
                });
        },
        setBatmans: function() {
            var i = this.getTossWinner();
            if (!this.isSecInn) {
                if (this.match_data.first_innings == 'bat') {
                    this.batting_team = this.match_data.teams[i].team_name;
                    this.batsmans = this.match_data.teams[i].players;
                } else if (this.match_data.first_innings == 'bowl') {
                    this.batting_team = this.match_data.teams[Math.abs(i - 1)].team_name;
                    this.batsmans = this.match_data.teams[Math.abs(i - 1)].players;
                }
            } else if (this.isSecInn) {
                if (this.match_data.first_innings == 'bat') {
                    this.fielding_team = this.match_data.teams[i].team_name;
                    this.fielders = this.match_data.teams[i].players;
                } else if (this.match_data.first_innings == 'bowl') {
                    this.fielding_team = this.match_data.teams[Math.abs(i - 1)].team_name;
                    this.fielders = this.match_data.teams[Math.abs(i - 1)].players;
                }
            }
        },
        setFielders: function() {
            var i = this.getTossWinner();
            if (!this.isSecInn) {
                if (this.match_data.first_innings == 'bowl') {
                    this.fielding_team = this.match_data.teams[i].team_name;
                    this.fielders = this.match_data.teams[i].players;
                } else if (this.match_data.first_innings == 'bat') {
                    this.fielding_team = this.match_data.teams[i].team_name;
                    this.fielders = this.match_data.teams[Math.abs(i - 1)].players;
                }
            } else if (this.isSecInn) {
                if (this.match_data.first_innings == 'bowl') {
                    this.batting_team = this.match_data.teams[i].team_name;
                    this.batsmans = this.match_data.teams[i].players;
                } else if (this.match_data.first_innings == 'bat') {
                    this.batting_team = this.match_data.teams[i].team_name;
                    this.batsmans = this.match_data.teams[Math.abs(i - 1)].players;
                }
            }
        },
        createBallConsumedArray: function() {
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
        resumeMatch: function() {
            var mainthis = this;
            axios.get('/getresumematchdata/' + mainthis.match_id)
                .then(function(response) {
                    console.log(response.data);
                    mainthis.setResumeBasic(response.data);
                }).catch(function(error) {
                    console.log(error);
                });
        },
        getTossWinner: function() {
            for (var i = 0; i < this.match_data.teams.length; i++) {
                if (this.match_data.teams[i].team_id == this.match_data.toss_winner) {
                    return i;
                }
            }
        },
        calcFirstInningsWicket: function() {
            var wicket = 0;
            for (var i = 0; i < this.ball_consumed.length; i++) {
                if (this.ball_consumed[i].out != null) {
                    wicket++;
                }
            }
            return wicket;
        },
        calculateBall: function(x) {
            for (var i = 0; i < this.ball_consumed.length; i++) {
                if (parseInt(x) == parseInt(this.ball_consumed[i].id)) {
                    return i;
                    break;
                }
            }
        },
        prepareSecInnings: function() {
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
    },
    filters: {
        convertOver: function(x) {
            var over = 0;
            var bowl = 0;
            if (x) {
                over = parseInt(eval(x / 6));
                bowl = eval(x % 6);
                return over + '.' + bowl;
            } else {
                return x;
            }
        }
    },
    computed: {
        countWicket: function() {
            return this.calcFirstInningsWicket();
        },
        calcRemainingBall: function() {
            return ((this.match_data.over * 6) - ((this.ball_data.current_over * 6) + this.ball_data.current_ball));
        },
        calcRemainingRun: function() {
            return this.first_innings.total_first - this.total_run + 1;
        },
        totalExtra: function() {
            var total = 0;
            for (var i = 0; i < this.extra_runs.length; i++) {
                total += this.extra_runs[i].extra;
            }
            return total;
        },
    }
});