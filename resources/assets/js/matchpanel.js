require('./bootstrap');

Vue.filter('convertOver', function(x) {
    var over = 0;
    var bowl = 0;
    if (x) {
        over = parseInt(eval(x / 6));
        bowl = eval(x % 6);
        return over + '.' + bowl;
    } else {
        return x;
    }
});

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
        },
        total_run: 0,
        last_ten: [],
        extra_runs: [],
        first_innings: {
            "total_first": 0,
            "first_inn_wicket": 0,
            "first_inn_over": 0
        }
    },
    created: function() {
        this.match_id = this.getMatchID();
        this.getMatchData();
    },
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
            axios.get('/getmatchdata/' + mainthis.match_id).then(function(response) {
                mainthis.match_data = response.data;
                mainthis.batsmans = mainthis.setBatmans();
                mainthis.fielders = mainthis.setFielders();
                mainthis.ball_consumed = [];
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
            }).catch(function(error) {
                console.log(error);
            });
        },
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
                axios.post('/getmatchdata/match/setinnings/' + this.match_data.match_id, {
                    match_id: this.match_data.match_id
                }).then(function(response) {
                    console.log(response.data);
                }).catch(function(error) {
                    console.log(error);
                });
            }
        },
        getTossWinner: function() {
            for (var i = 0; i < this.match_data.teams.length; i++) {
                if (this.match_data.teams[i].team_id == this.match_data.toss_winner) {
                    return i;
                }
            }
        },
        setBatmans: function() {
            var i = this.getTossWinner();
            if (this.match_data.first_innings == 'bat') {
                this.batting_team = this.match_data.teams[i].team_name;
                return this.match_data.teams[i].players;
            } else if (this.match_data.first_innings == 'bowl') {
                this.batting_team = this.match_data.teams[Math.abs(i - 1)].team_name;
                return this.match_data.teams[Math.abs(i - 1)].players;
            }
        },
        setFielders: function() {
            var i = this.getTossWinner();
            if (this.match_data.first_innings == 'bowl') {
                this.fielding_team = this.match_data.teams[i].team_name;
                return this.match_data.teams[i].players;;
            } else if (this.match_data.first_innings == 'bat') {
                this.fielding_team = this.match_data.teams[i].team_name;
                return this.match_data.teams[Math.abs(i - 1)].players;
            }
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
        addNewBall: function() {
            var mainthis = this;
            if (this.match_data.toss_winner != null && this.match_data.first_innings != null) {
                this.total_run += this.ball_data.ball_run;
                this.fillExtraRunArray();
                this.fillLastTenArray();
                axios.post('/getmatchdata/match/addnewball/' + this.match_data.match_id, {
                    player_bat: mainthis.on_strike.id,
                    player_bowl: mainthis.non_strike.id,
                    ball_number: mainthis.ball_data.current_over + '.' + mainthis.ball_data.current_ball,
                    incident: mainthis.ball_data.incident,
                    run: mainthis.ball_data.ball_run,
                    extra_type: mainthis.ball_data.extra_type
                }).then(function(response) {
                    console.log(response.data);
                }).catch(function(error) {
                    console.log(error);
                });
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
                this.bowler = '';
            }
        },
        setBallRun: function(run, local_extra_type, ball_incident) {
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
            this.addNewBall();
            if (local_extra_type != null) {
                this.isExtraBall = false;
            }
        },
        calculateBall: function(x) {
            for (var i = 0; i < this.ball_consumed.length; i++) {
                if (parseInt(x) == parseInt(this.ball_consumed[i].id)) {
                    return i;
                    break;
                }
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
        getPlayerName(x) {
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
                lastball += 'W';
            }
            if (this.last_ten.length >= 10) {
                this.last_ten.pop();
            }
            this.last_ten.unshift(lastball);
        }
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
        countWicket: function() {
            var wicket = 0;
            for (var i = 0; i < this.ball_consumed.length; i++) {
                if (this.ball_consumed[i].out != null) {
                    wicket++;
                }
            }
            return wicket;
        },
        totalExtra: function() {
            var total = 0;
            for (var i = 0; i < this.extra_runs.length; i++) {
                total += this.extra_runs[i].extra;
            }
            return total;
        }
    }
});