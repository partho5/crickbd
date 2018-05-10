@extends('base_layout') @section('page_content')

    {{--CSS--}}
    <link href="/assets/css/matchpanel.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/common-style.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
      rel="stylesheet"> {{--Fonts--}}
<link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<div class="col-md-8 col-md-offset-2" id="match-panel" style="padding: 0px;">
    {{--Score Board --}}
    <div v-if="!decideWinner">
        <div>
            <div id="upcoming-match" v-if="!checkToss" style="padding-bottom: 400px;margin-top: 20%;">
                <form class="form-startgame">
                    {{--
                    <h2 class="form-signin-heading"></h2>--}}
                    <?php
                    $time = strtotime($match->start_time);
                    $match_day = date('D, jS M, Y@ h:i A', $time);
                    ?>
                    <div class="match-detail-wrap">
                        <h2 class="team-name text-center">@{{ match_data.teams[0].team_name }} <span
                                    style="color: #636b6f;">vs</span> @{{ match_data.teams[1].team_name }}</h2>
                        <p><span class="over">{{ $match->over }} </span>overs match</p>
                        <p>Venue: <span class="venue"> {{ $match->location }}</span></p>
                        <p>Starts From <span class="start-date-time"> {{ $match_day }}</span></p>
                    </div>
                    <button @click="ask_start=!ask_start" class="btn btn-lg btn-primary btn-block" type="button">Start
                        Now
                    </button>
                    <div v-if="ask_start">
                        <div>
                            <div>
                                <p>
                                    Who Won The Toss?
                                </p>
                            </div>
                            <div>
                                <select @change="insertTossData" id="" name="" v-model="match_data.toss_winner">
                                    <option disabled selected>
                                        Select
                                    </option>
                                    <option :value="match_data.teams[0].team_id">
                                        @{{ match_data.teams[0].team_name }}
                                    </option>
                                    <option :value="match_data.teams[1].team_id">
                                        @{{ match_data.teams[1].team_name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <div>
                                <p>
                                    @{{ tossWinnerTeam }} choose to
                                </p>
                            </div>
                            <div>
                                <select @change="insertTossData" id="" name="" v-model="match_data.first_innings">
                                    <option disabled="" selected="" value="">
                                        Select
                                    </option>
                                    <option value="bat">
                                        Bat
                                    </option>
                                    <option value="bowl">
                                        Bowl
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div v-if="checkToss">
            <div id="body-head" style="margin-top: 50px;">
                <div id="today-match">
                    <p class="team-name">
                        @{{ match_data.teams[0].team_name }}
                        <span style="color: #636b6f;">
                                        vs
                    </span> @{{ match_data.teams[1].team_name }}
                    </p>
                    <div>
                        <div class="match-detail-wrap">
                            <h2 class="team-active">
                                @{{ battingTeam }}
                                <span class="run-active">@{{ total_run }}</span>/<span class="wicket">@{{ countWicket }}</span>
                                <span class="active-over">
                                                (@{{ ball_data.current_over }}.@{{ ball_data.current_ball }} over)
                            </span>
                            </h2>
                            <p class="inactive-team" v-if="isSecInn">
                                @{{ fieldingTeam }} @{{ first_innings.total_first }}/@{{ first_innings.first_inn_wicket
                                }} (@{{ first_innings.first_inn_over }} over)
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
            </div>
            <div class="run-table">
                <p class="table-name">
                    Run Table
                    <span title="Undo Last Event">
                    <button @click="undoLastBall">
                                        Undo
                    </button>
                </span>
                </p>
                <div class="add-run">
                    <div class="col-md-12" style="margin-top: 5px;" v-if=" on_strike.id && non_strike.id && bowler">
                        <div class="col-md-1 btn-assigner">
                            Add
                        </div>
                        <div class="col-md-8">
                            <div class="col-md-12" v-if="!isExtraBall">
                                <div class="col-md-6">
                                    <button @click="setBallRun(0,null,null)" class="btn btn-ghost" value="0">
                                        0
                                    </button>
                                    <button @click="setBallRun(1,null,null)" class="btn btn-full">
                                        1
                                    </button>
                                    <button @click="setBallRun(2,null,null)" class="btn btn-ghost">
                                        2
                                    </button>
                                    <button @click="setBallRun(3,null,null)" class="btn btn-full">
                                        3
                                    </button>
                                </div>
                                <div class="col-md-6">

                                    <button @click="setBallRun(4,null,null)" class="btn btn-ghost">
                                        4
                                    </button>
                                    <button @click="setBallRun(5,null,null)" class="btn btn-full">
                                        5
                                    </button>
                                    <button @click="setBallRun(6,null,null)" class="btn btn-ghost">
                                        6
                                    </button>

                                    <button @click="setBallRun(7,null,null)" class="btn btn-full">
                                        7
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                {{--Noball--}}
                                <div class="col-md-4" style="margin-bottom: 4px;">
                                <span class="dropdown" v-if="isExtraBall">
                                                    <button class="btn btn-primary dropdown-toggle"
                                                            data-toggle="dropdown" type="button">
                                                        No Ball
                                                        <span class="caret">
                                                        </span>
                                </button>
                                <ul class="dropdown-menu extra-run-a">

                                        <li @click='setBallRun(0,"nb",null)' class="">
                                            +0
                                        </li>


                                        <li @click='setBallRun(1,"nb",null)' class="">
                                            +1
                                        </li>


                                        <li @click='setBallRun(2,"nb",null)' class="">
                                            +2
                                        </li>


                                        <li @click='setBallRun(3,"nb",null)' class="">
                                            +3
                                        </li>


                                        <li @click='setBallRun(4,"nb",null)' class="">
                                            +4
                                        </li>

                                        <li @click='setBallRun(6,"nb",null)' class="">
                                            +6
                                        </li>
                                </ul>
                                </span>
                                </div>
                                {{--By--}}
                                <div class="col-md-4" style="margin-bottom: 4px;">
                                <span class="dropdown" v-if="isExtraBall">
                                <button aria-expanded="false" aria-haspopup="true"
                                        class="btn btn-primary dropdown-toggle" data-toggle="dropdown" role="button"
                                        type="button">
                                    By Run
                                    <span class="caret">
                                    </span>
                                </button>
                                <ul class="dropdown-menu extra-run-a">
                                    
                                        <li @click='setBallRun(0,"by",null)' class="">
                                            0
                                        </li>
                                    
                                    
                                        <li @click='setBallRun(1,"by",null)' class="">
                                            1
                                        </li>
                                    
                                        <li @click='setBallRun(2,"by",null)' class="">
                                            2
                                        </li>
                                    
                                        <li @click='setBallRun(3,"by",null)' class="">
                                            3
                                        </li>
                                    
                                        <li @click='setBallRun(4,"by",null)' class="">
                                            4
                                        </li>
                                    
                                    
                                        <li @click='setBallRun(6,"by",null)' class="">
                                            6
                                        </li>
                                </ul>
                                </span>
                                </div>
                                {{--Wide--}}
                                <div class="col-md-4" style="margin-bottom: 4px;">
                                <span class="dropdown" v-if="isExtraBall">
                                                    <button aria-expanded="false" aria-haspopup="true"
                                                            class="btn btn-primary dropdown-toggle btn-full"
                                                            data-toggle="dropdown" role="button" type="button">
                                                        Wide
                                                        <span class="caret">
                                                        </span>
                                </button>
                                <ul class="dropdown-menu extra-run-a">
                                    
                                        <li @click='setBallRun(0,"wd",null)' class="">
                                            +0
                                        </li>
                                    
                                        <li @click='setBallRun(1,"wd",null)' class="">
                                            +1
                                        </li>
                                    
                                        <li @click='setBallRun(2,"wd",null)' class="">
                                            +2
                                        </li>
                                    
                                    
                                        <li @click='setBallRun(3,"wd",null)' class="">
                                            +3
                                        </li>
                                    
                                        <li @click='setBallRun(4,"wd",null)' class="">
                                            +4
                                        </li>
                                    
                                        <li @click='setBallRun(6,"wd",null)' class="">
                                            +6
                                        </li>
                                </ul>
                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button @click="isExtraBall=!isExtraBall" class="btn default" value="0">
                                Extra Run?
                            </button>
                        </div>
                    </div>
                </div>
                <div class="add-run">
                    <div class="col-md-12" v-if="(bowler && on_strike.id && non_strike.id) || inningsEnd">
                        <div class="col-md-9">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-full" @click="endInnings">
                                End Session
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{--Run table End--}} {{--Batting table--}}
            <div class="batting-table">
                <p class="table-name">
                    Batting: @{{ battingTeam }}
                </p>
                <table>
                    <thead>
                        <th>
                            Name(Jersey No)
                        </th>
                        <th>
                            Run(s)
                        </th>
                        <th>
                            Ball(s)
                        </th>
                        <th>
                            Status
                        </th>
                    </thead>
                    <tr :class="{ playing:(player.player_id==on_strike.id) && alreadyOut(calculateBall(player.player_id)),off_strike:(player.player_id==non_strike.id) && alreadyOut(calculateBall(player.player_id)),player_out:!alreadyOut(calculateBall(player.player_id)) }"
                        v-for="player in batsmans">
                        <td>
                            @{{ player.player_name }}
                            <span v-if="player.jersey!=null">
                                            (@{{ player.jersey }})
                                        </span>
                        </td>
                        <td>
                            @{{ ball_consumed[calculateBall(player.player_id)].run }}
                        </td>
                        <td>
                            @{{ ball_consumed[calculateBall(player.player_id)].ball }}
                        </td>
                        <td v-if="alreadyOut(calculateBall(player.player_id))">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="col-md-5">
                                     <span class="dropdown" v-if="!inningsEnd">
                                <button class="btn-primary dropdown-toggle btn-full" data-toggle="dropdown" type="button">
                                    Send
                                    <span class="caret">
                                                </span>
                                </button>
                                <ul class="dropdown-menu">
                                    <button :value="player.player_id" @click="strikeBat(player.player_id)"
                                            class="btn btn-full">
                                        :::::On-Strike:::
                                    </button>
                                    <button :value="player.player_id" @click="nonStrikeBat(player.player_id)"
                                            class="btn btn-ghost">
                                        :::Non-strike:::
                                    </button>
                                </ul>
                            </span>
                                </div>
                                <div class="col-md-5">
                                    {{--Added New Button--}}
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Batting
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li>Bowled</li>
                                            <li>CatchOut</li>
                                            <li>LBW</li>
                                            <li class="dropdown-submenu runout-dropdown-submenu">
                                                <div class="test" tabindex="-1"> RunOut <span class="caret"></span></div>
                                                <ul class="dropdown-menu">
                                                    <li>Out +0 Run</li>
                                                    <li>Out +1 Run</li>
                                                    <li>Out +2 Run</li>
                                                    <li>Out +nb +0 Run</li>
                                                    <li>Out +nb +1 Run</li>
                                                    <li>Out +nb +2 Run</li>
                                                    <li>Out +by 1 Run</li>
                                                    <li>Out +By1 Run</li>
                                                    <li>Out +2 Run</li>
                                                    <li>Out +0 Run</li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div>



                            </div>
                        </td>
                        <td v-else>
                            @{{ ball_consumed[calculateBall(player.player_id)].out }} (@{{
                            getPlayerName(ball_consumed[calculateBall(player.player_id)].w_taker) }})
                        </td>
                    </tr>
                </table>
            </div>
            {{--Batting Table End--}} {{--Bowling Table--}}
            <div class="bowling-table">
                <p class="table-name">
                    Bowling: @{{ fieldingTeam }}
                </p>
                <table>
                    <thead>
                        <th>
                            Name(Jersey No)
                        </th>
                        <th>
                            Over(s)
                        </th>
                        <th>
                            Run(s)
                        </th>
                        <th>
                            Status
                        </th>
                    </thead>
                    <tr :class="{ playing:player.player_id==bowler }" v-for="player in fielders">
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
                        <td v-if="!inningsEnd">
                            <button @click="setBowler(player.player_id)" class="status-btn"
                                    v-if="player.player_id!=old_bowler">
                            <span v-if="player.player_id!=bowler">
                                                Active
                                            </span>
                                <span v-else="">
                                                bowling
                                            </span>
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
            {{--Bowling Table End--}}
            <p class="table-name">
                Extra Runs
            </p>
            <div class="add-run">
                <div class="col-md-12">
                    <div class="recent-notifications">
                        <p class="extra-runs" style="font-size: 16pt">
                        <span v-for="(run,index) in extra_runs">
                            <span v-if="index>0">+</span> @{{ run.extra }}@{{ run.type }}
                        </span>
                            <span>
                            = @{{ totalExtra }} Extra Run
                        </span>
                        </p>
                        <br>
                        <br>
                        <h3>
                            Recent Activities:
                        </h3>
                        <p>
                            Partnership: @{{ partnership.run }} Runs from @{{ partnership.ball }} ball(s)
                        </p>
                        <p>
                            Recent Balls:
                            <span v-for="(ball,index) in last_ten">
                            <span v-if="index>=1">|</span> @{{ ball }}
                        </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dropdown">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Batting
            <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li>Bowled</li>
            <li>CatchOut</li>
            <li>LBW</li>
            <li class="dropdown-submenu runout-dropdown-submenu">
                <div class="test" tabindex="-1"> RunOut <span class="caret"></span></div>
                <ul class="dropdown-menu">
                    <li>Out +0 Run</li>
                    <li>Out +1 Run</li>
                    <li>Out +2 Run</li>
                    <li>Out +nb +0 Run</li>
                    <li>Out +nb +1 Run</li>
                    <li>Out +nb +2 Run</li>
                    <li>Out +by 1 Run</li>
                    <li>Out +By1 Run</li>
                    <li>Out +2 Run</li>
                    <li>Out +0 Run</li>
                </ul>
            </li>
        </ul>
    </div>
    <div v-if="decideWinner">
        @include('layouts.decide_winner')
    </div>
</div>
<script src="/js/wicketdropdown.js"></script>
<script src="{{ mix('/js/matchpanel.js') }}"></script>
@endsection