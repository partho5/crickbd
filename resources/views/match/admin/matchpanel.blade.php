@extends('base_layout')


@section('page_content')
    {{--CSS--}}
    <link rel="stylesheet" href="/assets/css/matchpanel.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    {{--Fonts--}}
    <link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">


    <div class="col-md-8 col-md-offset-2" id="match-panel" style="padding: 0px;">

        {{--Score Board --}}

        <div v-if="!checkToss">
            <div style="padding-top: 50px;">
                <button class="btn btn-danger" @click="ask_start=!ask_start">Start Match</button>
            </div>
            <div v-if="ask_start">
                <div>
                    <div>
                        <p>Who Won The Toss?</p>
                    </div>
                    <div>
                        <select name="" id="" v-model="match_data.toss_winner" @change="insertTossData">
                            <option value="" selected disabled>Select</option>
                            <option :value="match_data.teams[0].team_id">@{{ match_data.teams[0].team_name }}</option>
                            <option :value="match_data.teams[1].team_id">@{{ match_data.teams[1].team_name }}</option>
                        </select>
                    </div>
                </div>
                <div>
                    <div>
                        <p>@{{ tossWinnerTeam }} choose to </p>
                    </div>
                    <div>
                        <select name="" id="" v-model="match_data.first_innings" @change="insertTossData">
                            <option value="" selected disabled>Select</option>
                            <option value="bat"> Bat</option>
                            <option value="bowl"> Bowl</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="checkToss">
            <div id="body-head" style="margin-top: 50px;">
                <div id="today-match">
                    <p class="team-name">@{{ match_data.teams[0].team_name }} <span style="color: #636b6f;">vs</span>
                        @{{ match_data.teams[1].team_name }}</p>
                    <div>
                        <div class="match-detail-wrap">
                            <p class="team-active">@{{ batting_team }} <span class="run-active">000</span>/<span
                                        class="wicket">0</span> <span class="active-over"> (0.0 over)</span></p>
                            <p class="inactive-team" v-if="isSecInn">@{{ fielding_team }} 000/0 (0 over)</p>
                            <p class="inactive-team" v-if="isSecInn">@{{ batting_team }} need <span class="run-active">000</span>
                                runs in <span class="ball-left">000</span> balls</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="run-table">
                <p class="table-name">Run Table <span title="Undo Last Event"><button>Undo</button></span></p>

                <div class="add-run">
                    <div class="col-md-12">
                        <div class="col-md-1 btn-assigner">Add</div>
                        <div class="col-md-8">
                        <span v-if="!isExtraBall">
                            <button class="btn zero" @click="setBallRun(0,null,null)" value="0">0</button>
                            <button class="btn one" @click="setBallRun(1,null,null)">1</button>
                            <button class="btn two" @click="setBallRun(2,null,null)">2</button>
                            <button class="btn three" @click="setBallRun(3,null,null)">3</button>
                            <button class="btn four" @click="setBallRun(4,null,null)">4</button>
                            <button class="btn six" @click="setBallRun(6,null,null)">6</button>
                        </span>

                            {{--Noball--}}

                            <span class="dropdown" v-if="isExtraBall">
-                               <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                    No Ball<span class="caret"></span>
                                </button>
-                               <ul class="dropdown-menu">
-                                   <button class="btn zero" @click='setBallRun(0,"nb",null)'>+0</button>
-                                   <button class="btn one" @click='setBallRun(1,"nb",null)'>+1</button>
-                                   <button class="btn two" @click='setBallRun(2,"nb",null)'>+2</button>
-                                   <button class="btn three" @click='setBallRun(3,"nb",null)'>+3</button>
-                                   <button class="btn four" @click='setBallRun(4,"nb",null)'>+4</button>
-                                   <button class="btn six" @click='setBallRun(6,"nb",null)'>+6</button>
-                               </ul>
                            </span>

                            {{--By--}}

                            <span class="dropdown" v-if="isExtraBall">
-                               <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                    By<span class="caret"></span>
                                </button>
-                               <ul class="dropdown-menu">
-                                   <button class="btn zero" @click='setBallRun(0,"by",null)'>0</button>
-                                   <button class="btn one" @click='setBallRun(1,"by",null)'>1</button>
-                                   <button class="btn two" @click='setBallRun(2,"by",null)'>2</button>
-                                   <button class="btn three" @click='setBallRun(3,"by",null)'>3</button>
-                                   <button class="btn four" @click='setBallRun(4,"by",null)'>4</button>
-                                   <button class="btn six" @click='setBallRun(6,"by",null)'>6</button>
-                               </ul>
                            </span>

                            {{--Wide--}}

                            <span class="dropdown" v-if="isExtraBall">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Wide
                            </button>
                        </span>


                        </div>
                        <div class="col-md-3">
                            <button class="btn default" value="0" @click="isExtraBall=!isExtraBall">Extra Run?</button>
                            {{-- <select name="" id="out">
                                <option selected disabled>Out</option>
                                <option value="">Bowled</option>
                                <option value="">RunOut</option>
                                <option value="">CatchOut</option>
                            </select> --}}
                        </div>
                    </div>
                </div>
                <div class="add-run">
                    <div class="col-md-12" v-if="isExtraBall">

                        <div class="col-md-8">
                            {{--<span class="dropdown">--}}
                            {{--<select>--}}
                            {{--<option value="" selected disabled >Noball<span class="caret"></span></option>--}}
                            {{--<option class="btn zero" value="0">+0</option>--}}
                            {{--<option class="btn one"  value="1">+1</option>--}}
                            {{--<option class="btn two" value="2">+2</option>--}}
                            {{--<option class="btn three" value="3">+3</option>--}}
                            {{--<option class="btn four" value="4">+4</option>--}}
                            {{--<option class="btn six" value="6">+6</option>--}}
                            {{--</select>--}}
                            {{--</span>--}}

                            {{--<span class="dropdown">--}}
                            {{--<select>--}}
                            {{--<option class="extra" value="" selected disabled >By<span class="caret"></span></option>--}}
                            {{--<option class="extra zero" value="0">0</option>--}}
                            {{--<option class="extra one"  value="1">1</option>--}}
                            {{--<option class="extra two" value="2">2</option>--}}
                            {{--<option class="extra three" value="3">3</option>--}}
                            {{--<option class="extra four" value="4">4</option>--}}
                            {{--<option class="extra six" value="6">6</option>--}}
                            {{--</select>--}}
                            {{--</span>--}}


                            {{--<span class="dropdown">--}}
                            {{--<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Wide--}}
                            {{--</button>--}}
                            {{--<span class="caret"></span></button>--}}
                            {{--<ul class="dropdown-menu">--}}
                            {{--<button class="btn zero" value="0">+by 0</button>--}}
                            {{--<button class="btn one"  value="1">+by 1</button>--}}
                            {{--<button class="btn two" value="2">+by 2</button>--}}
                            {{--<button class="btn three" value="3">+by 3</button>--}}
                            {{--<button class="btn four" value="4">+by 4</button>--}}
                            {{--<button class="btn six" value="6">+by 6</button>--}}
                            {{--</ul>--}}
                            {{--</span>--}}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <button class="btn two" value="2">End Session</button>
                        </div>
                    </div>
                </div>
            </div>


            {{--Run table End--}}


            {{--Batting table--}}

            <div class="batting-table">
                <p class="table-name">Bating Table</p>
                <table>
                    <tr>
                        <th>Name(Jersey No)</th>
                        <th>Run(s)</th>
                        <th>Ball(s)</th>
                        <th>Status</th>
                    </tr>

                    <tr v-for="player in batsmans"
                        :class="{ playing:player.player_id==on_strike,off_strike:player.player_id==non_strike }">
                        <td>@{{ player.player_name }} <span v-if="player.jersey!=null"> (@{{ player.jersey }})</span>
                        </td>
                        <td>00</td>
                        <td>00</td>
                        <td>
                        <span class="dropdown">
                            <button class="btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Send
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <button class="btn zero" :value="player.player_id" @click="strikeBat(player.player_id)">On-Strike</button>
                                <button class="btn one" :value="player.player_id"
                                        @click="nonStrikeBat(player.player_id)">Non-strike</button>
                            </ul>
                            <select name="" id="out" v-if="player.player_id==on_strike || player.player_id==non_strike">
                                <option selected disabled>Out</option>
                                <option value="" v-show="player.player_id==on_strike">Bowled</option>
                                <option value="" v-show="player.player_id==on_strike || player.player_id==non_strike">RunOut</option>
                                <option value="" v-show="player.player_id==on_strike">CatchOut</option>
                                <option value="" v-show="player.player_id==on_strike">LBW</option>
                            </select>
                        </span>
                        </td>
                    </tr>
                    {{-- <tr>
                        <td>Sourav</td>
                        <td>54</td>
                        <td>65</td>
                        <td>
                            <span class="dropdown">
                                <button class="btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Send
                                    <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <button class="btn zero" value="0">On-Strike</button>
                                    <button class="btn one"  value="1">Non-strike</button>
                                </ul>
                            </span>
                        </td>
                    </tr>
                    <tr class="playing">
                        <td>Sourav</td>
                        <td>54</td>
                        <td>65</td>
                        <td>
                            <span class="dropdown">
                                <button class="btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Send
                                    <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <button class="btn zero" value="0">On-Strike</button>
                                    <button class="btn one"  value="1">Non-strike</button>
                                </ul>
                            </span>
                        </td>
                    </tr>
                    <tr class="out">
                        <td>Sourav Kumar Pramanik</td>
                        <td>54</td>
                        <td>65</td>
                        <td>
                            <span class="dropdown">
                                <button class="btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Send
                                    <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <button class="btn zero" value="0">On-Strike</button>
                                    <button class="btn one"  value="1">Non-strike</button>
                                </ul>
                            </span>
                        </td>
                    </tr> --}}
                </table>
            </div>

            {{--Batting Table End--}}


            {{--Bowling Table--}}


            <div class="bowling-table">
                <p class="table-name">Bowling Table</p>
                <table>
                    <tr>
                        <th>Name(Jersey No)</th>
                        <th>Over(s)</th>
                        <th>Run(s)</th>
                        <th>Status</th>
                    </tr>

                    <tr v-for="player in fielders" :class="{ playing:player.player_id==bowler }">
                        <td>@{{ player.player_name }} <span v-if="player.jersey!=null"> (@{{ player.jersey }})</span>
                        </td>
                        <td>00</td>
                        <td>00</td>
                        <td>
                            <button class="status-btn" @click="setBowler(player.player_id)">Active</button>
                        </td>
                    </tr>
                    {{-- <tr>
                        <td>Sourav</td>
                        <td>54</td>
                        <td>65</td>
                        <td><button class="status-btn" >Active</button></td>
                    </tr>
                    <tr class="playing">
                        <td>Sourav</td>
                        <td>54</td>
                        <td>65</td>
                        <td><button class="status-btn" >Active</button></td>
                    </tr>
                    <tr class="out">
                        <td>Sourav Kumar Pramanik</td>
                        <td>54</td>
                        <td>65</td>
                        <td><button class="status-btn" >Active</button></td>
                    </tr>
                    <tr>
                        <td>Sourav</td>
                        <td>54</td>
                        <td>65</td>
                        <td><button class="status-btn" >Active</button></td>
                    </tr>
                    <tr>
                        <td>Sourav</td>
                        <td>54</td>
                        <td>65</td>
                        <td><button class="status-btn" >Active</button></td>
                    </tr>
                    <tr>
                        <td>Sourav</td>
                        <td>54</td>
                        <td>65</td>
                        <td><button class="status-btn" >Active</button></td>
                    </tr> --}}
                </table>
            </div>


            {{--Bowling Table End--}}


            <p class="table-name">Extra Runs</p>
            <div class="add-run">
                <div class="col-md-12">
                    <div class="recent-notifications">
                        <p class="extra-runs">1 3 2 3 1 ..... = 23 Total</p>
                        <br><br>
                        <h3>Recent Activities: </h3>
                        <p>Partneership: 25 Runs from 16 ball(s)</p>
                        <p>Recent Balls: 0 2 0 1 0 6 | 0 1 0 </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="{{ mix('/js/matchpanel.js') }}"></script>
@endsection