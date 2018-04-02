@extends('base_layout')
@section('page_content')
    <link rel="stylesheet" href="/assets/css/scoreboard.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> {{--Fonts--}}
    <link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <div class="col-md-8 col-md-offset-2" style="padding: 0;" id="detail">
        <section id="main-body" style="margin-top: 50px;">
            <div id="match-main-scoreboard">
                <p class="team-name">
                    @{{ match_data.teams[0].team_name }}
                    <span style="color: #636b6f;">vs</span> @{{ match_data.teams[1].team_name }}
                </p>
                <div>
                    <div class="match-detail-wrap">
                        <p class="team-active">@{{ battingTeam }} <span class="run-active"> @{{ total_run }}</span>/<span
                                    class="wicket">@{{ countWicket }}</span> <span class="active-over"> (@{{ ball_data.current_over }}.@{{ ball_data.current_ball }} over)</span>
                        </p>
                        <p class="inactive-team" v-if="!isSecInn && checkToss">
                            <strong>@{{ tossWinnerTeam }}</strong> won the toss and choose to <strong>@{{ match_data.first_innings }}</strong>
                        </p>
                        <p class="inactive-team" v-if="isSecInn">
                            @{{ fieldingTeam }} @{{ first_innings.total_first }}/@{{ first_innings.first_inn_wicket }} (@{{
                            first_innings.first_inn_over }} over)
                        </p>
                        <p class="inactive-team" v-if="isSecInn">
                            @{{ battingTeam }} need
                            <span class="run-active">
                                                @{{ calcRemainingRun }}
                            </span> runs in
                            <span class="ball-left">
                                                @{{ calcRemainingBall }}
                            </span> balls
                        </p>
                    </div>
                </div>
            </div>
            {{--First Innings--}}
            <ul class="innings-toggle">
                <li class="first"><a href="">1st innings</a></li>
                <li class="second"><a href="">2nd innings
                    </a></li>
            </ul>
            <div class="first-innings">
                <p class="innings-no">1st Innings</p>
                <div class="batting-table">
                    <p class="table-name">Batting: team_name </p>
                    <table>
                        <tr>
                            <th>Name(Jersey No)</th>
                            <th>Run(s)</th>
                            <th>Ball(s)</th>
                        </tr>
                        <tr v-for="player in batsmans"
                            :class="{ playing:player.player_id==on_strike.id,off_strike:player.player_id==non_strike.id }">
                            <td>@{{ player.player_name }}
                                <span v-if="player.jersey!=null">
                            (@{{ player.jersey }})
                        </span>
                                <span v-if="!alreadyOut(calculateBall(player.player_id))"
                                      style="font-style: italic; font-size: 10pt;">
                            @{{ ball_consumed[calculateBall(player.player_id)].out }}(@{{ getPlayerName(ball_consumed[calculateBall(player.player_id)].w_taker) }})
                        </span>
                            </td>
                            <td>
                                @{{ ball_consumed[calculateBall(player.player_id)].run }}
                            </td>
                            <td>
                                @{{ ball_consumed[calculateBall(player.player_id)].ball }}
                            </td>
                        </tr>
                    </table>
                </div>
                <br>
                <div class="recent-notifications">
                    <p>Partnership: @{{ partnership.run }} Runs from @{{ partnership.ball }} ball(s)</p>
                    <p>Recent Balls:
                        <span v-for="(ball,index) in last_ten">
                    <span v-if="index>=1">|</span> @{{ ball }}
                </span>
                    </p>
                    <p class="extra-runs" style="font-size: 16pt">
                <span v-for="(run,index) in extra_runs">
                    <span v-if="index>0">+</span> @{{ run.extra }}@{{ run.type }}
                </span>
                        <span>
                    = @{{ totalExtra }} Extra Run
                </span>
                    </p>
                </div>
                <div class="bowling-table">
                    <p class="table-name">Bowling: team_name</p>
                    <table>
                        <tr>
                            <th>Name(Jersey No)</th>
                            <th>Over(s)</th>
                            <th>Run</th>
                            <th>Wicket(s)</th>
                        </tr>
                        <tr v-for="player in fielders" :class="{ playing:player.player_id==bowler }">
                            <td>
                                @{{ player.player_name }}
                                <span v-if="player.jersey!=null">
                            (@{{ player.jersey }})
                        </span>
                            </td>
                            <td>
                                @{{ ball_consumed[calculateBall(player.player_id)].ball | convertOver }}
                            </td>
                            <td>
                                @{{ ball_consumed[calculateBall(player.player_id)].run }}
                            </td>
                            <td>
                                @{{ countBowlerWicket(player.player_id) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            {{--Second Innings--}}
            <div class="second-innings">
                <p class="innings-no">2nd Innings</p>
                <div class="batting-table">
                    <p class="table-name">Batting: team_name</p>
                    <table>
                        <tr>
                            <th>Name(Jersey No)</th>
                            <th>Run(s)</th>
                            <th>Ball(s)</th>
                        </tr>
                        <tr v-for="player in batsmans"
                            :class="{ playing:player.player_id==on_strike.id,off_strike:player.player_id==non_strike.id }">
                            <td>@{{ player.player_name }}
                                <span v-if="player.jersey!=null">
                            (@{{ player.jersey }})
                        </span>
                                <span v-if="!alreadyOut(calculateBall(player.player_id))"
                                      style="font-style: italic; font-size: 10pt;">
                            @{{ ball_consumed[calculateBall(player.player_id)].out }}(@{{ getPlayerName(ball_consumed[calculateBall(player.player_id)].w_taker) }})
                        </span>
                            </td>
                            <td>
                                @{{ ball_consumed[calculateBall(player.player_id)].run }}
                            </td>
                            <td>
                                @{{ ball_consumed[calculateBall(player.player_id)].ball }}
                            </td>
                        </tr>
                    </table>
                </div>
                <br>
                <div class="recent-notifications">
                    <p>Partnership: @{{ partnership.run }} Runs from @{{ partnership.ball }} ball(s)</p>
                    <p>Recent Balls:
                        <span v-for="(ball,index) in last_ten">
                    <span v-if="index>=1">|</span> @{{ ball }}
                </span>
                    </p>
                    <p class="extra-runs" style="font-size: 16pt">
                <span v-for="(run,index) in extra_runs">
                    <span v-if="index>0">+</span> @{{ run.extra }}@{{ run.type }}
                </span>
                        <span>
                    = @{{ totalExtra }} Extra Run
                </span>
                    </p>
                </div>
                <div class="bowling-table">
                    <p class="table-name">Bowling: team_name</p>
                    <table>
                        <tr>
                            <th>Name(Jersey No)</th>
                            <th>Over(s)</th>
                            <th>Run</th>
                            <th>Wicket(s)</th>
                        </tr>
                        <tr v-for="player in fielders" :class="{ playing:player.player_id==bowler }">
                            <td>
                                @{{ player.player_name }}
                                <span v-if="player.jersey!=null">
                            (@{{ player.jersey }})
                        </span>
                            </td>
                            <td>
                                @{{ ball_consumed[calculateBall(player.player_id)].ball | convertOver }}
                            </td>
                            <td>
                                @{{ ball_consumed[calculateBall(player.player_id)].run }}
                            </td>
                            <td>
                                @{{ countBowlerWicket(player.player_id) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection
