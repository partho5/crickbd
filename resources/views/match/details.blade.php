@extends('base_layout') @section('page_content') {{--CSS--}}
<link rel="stylesheet" href="/assets/css/details.css">
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> {{--Fonts--}}
<link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<div class="col-md-8 col-md-offset-2" style="padding: 0;" id="detail">
    <div v-if="!decideWinner">
        <section id="main-body" style="margin-top: 50px;">
            <div id="today-match">
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
                        {{--<a :href='"/scoreboard/"+match_id'>More Details</a>--}}
                    </div>
                </div>
            </div>
            <div class="batting-table">
                <p class="table-name">Batting Table</p>
                <table>
                    <tr>
                        <th>Name(Jersey No)</th>
                        <th>Run(s)</th>
                        <th>Ball(s)</th>
                    </tr>
                    <tr v-for="player in batsmans"
                        :class="{ playing:(player.player_id==on_strike.id) && alreadyOut(calculateBall(player.player_id)),off_strike:(player.player_id==non_strike.id) && alreadyOut(calculateBall(player.player_id)),player_out:!alreadyOut(calculateBall(player.player_id)) }">
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
                <p class="table-name">Bowling Table</p>
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
        </section>
    </div>
    <div v-if="decideWinner">
        @include('layouts.decide_winner')
    </div>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
<script type="text/javascript" src="/js/basicMixin.js"></script>
<script type="text/javascript" src="/js/details.js"></script>
@endsection