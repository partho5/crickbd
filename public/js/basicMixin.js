/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 47);
/******/ })
/************************************************************************/
/******/ ({

/***/ 37:
/***/ (function(module, exports) {

Vue.mixin({
    methods: {
        getMatchID: function getMatchID() {
            var url = window.location.href;
            for (var i = url.length - 1; i >= 0; i--) {
                if (url[i] == '/') {
                    break;
                }
            }
            return Number(url.slice(i + 1));
        },
        getMatchData: function getMatchData() {
            var mainthis = this;
            axios.get('/getmatchdata/' + mainthis.match_id).then(function (response) {
                mainthis.match_data = response.data;
                mainthis.setBatmans();
                mainthis.setFielders();
                mainthis.ball_consumed = [];
                mainthis.createBallConsumedArray();
                mainthis.resumeMatch();
            }).catch(function (error) {
                console.log(error);
            });
        },
        setBatmans: function setBatmans() {
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
        setFielders: function setFielders() {
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
        createBallConsumedArray: function createBallConsumedArray() {
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
        resumeMatch: function resumeMatch() {
            var mainthis = this;
            axios.get('/getresumematchdata/' + mainthis.match_id).then(function (response) {
                console.log(response.data);
                mainthis.setResumeBasic(response.data);
            }).catch(function (error) {
                console.log(error);
            });
        },
        getTossWinner: function getTossWinner() {
            for (var i = 0; i < this.match_data.teams.length; i++) {
                if (this.match_data.teams[i].team_id == this.match_data.toss_winner) {
                    return i;
                }
            }
        },
        getPlayerName: function getPlayerName(x) {
            for (var i = 0; i < this.match_data.teams.length; i++) {
                for (j = 0; j < this.match_data.teams[i].players.length; j++) {
                    if (this.match_data.teams[i].players[j].player_id == x) {
                        return this.match_data.teams[i].players[j].player_name;
                        break;
                    }
                }
            }
        },
        alreadyOut: function alreadyOut(x) {
            if (this.ball_consumed[x].out == null) {
                return true;
            } else {
                return false;
            }
        },
        calcFirstInningsWicket: function calcFirstInningsWicket() {
            var wicket = 0;
            for (var i = 0; i < this.ball_consumed.length; i++) {
                if (this.ball_consumed[i].out != null) {
                    wicket++;
                }
            }
            return wicket;
        },
        calculateBall: function calculateBall(x) {
            for (var i = 0; i < this.ball_consumed.length; i++) {
                if (parseInt(x) == parseInt(this.ball_consumed[i].id)) {
                    return i;
                    break;
                }
            }
        },
        prepareSecInnings: function prepareSecInnings() {
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
        setResumeBasic: function setResumeBasic(resume_data) {
            var x = resume_data[1];
            if (resume_data[0].stage != 'before_match_start' && resume_data[0].stage != 'first_innings_ended') {
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
            }
        },
        countBowlerWicket: function countBowlerWicket(x) {
            var wicket = 0;
            this.ball_consumed.forEach(function (item, index) {
                if (item.w_taker == x) {
                    wicket++;
                }
            });
            return wicket;
        }
    },
    filters: {
        convertOver: function convertOver(x) {
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
        countWicket: function countWicket() {
            return this.calcFirstInningsWicket();
        },
        calcRemainingBall: function calcRemainingBall() {
            return this.match_data.over * 6 - (this.ball_data.current_over * 6 + this.ball_data.current_ball);
        },
        calcRemainingRun: function calcRemainingRun() {
            return this.first_innings.total_first - this.total_run + 1;
        },
        totalExtra: function totalExtra() {
            var total = 0;
            for (var i = 0; i < this.extra_runs.length; i++) {
                total += this.extra_runs[i].extra;
            }
            return total;
        },
        battingTeam: function battingTeam() {
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
        fieldingTeam: function fieldingTeam() {
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
        checkToss: function checkToss() {
            if (this.match_data.first_innings != null) {
                return true;
            } else {
                return false;
            }
        },
        tossWinnerTeam: function tossWinnerTeam() {
            var toss_winner = this.getTossWinner();
            if (typeof toss_winner != 'undefined') {
                return this.match_data.teams[toss_winner].team_name;
            } else {
                return 'No Team';
            }
        }
    }
});

/***/ }),

/***/ 47:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(37);


/***/ })

/******/ });