@extends('base_layout')
@section('page_content')
    <link rel="stylesheet" href="/assets/css/scoreboard.css">
    <link rel="stylesheet" href="/assets/css/common-style.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> {{--Fonts--}}
    <link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,300italic" rel="stylesheet" type="text/css">
    <div class="col-md-8 col-md-offset-2" style="padding: 0;" id="score">
        <section id="main-body" style="margin-top: 50px;">
            <div id="match-main-scoreboard">
                <p class="team-name">
                    {{ $match_data[0]['teams'][0]['team_name'] }}
                    <span style="color: #636b6f;">vs</span> {{ $match_data[0]['teams'][1]['team_name'] }}
                </p>
                <div>
                    <div class="match-detail-wrap">
                        <p class="team-active">{{ $scores['basic']['batting_team']  }} <span
                                    class="run-active"> {{ $scores['first']['runs'] }}</span>/<span
                                    class="wicket">{{ $scores['first']['wickets'] }}</span> <span
                                    class="active-over"> ({{ $scores['first']['overs'] }} ov)</span>
                        </p>
                        @if($scores['second']['runs'] !=null || $scores['second']['overs'] !=null )
                            <p class="team-active">{{ $scores['basic']['bowling_team']  }} <span
                                        class="run-active"> {{ $scores['second']['runs'] }}</span>/<span
                                        class="wicket">{{ $scores['second']['wickets'] }}</span> <span
                                        class="active-over"> ({{ $scores['second']['overs'] }} ov)</span>
                            </p>
                        @endif
                        <p class="inactive-team">
                            <strong>{{ $scores['basic']['toss_winner_team_name'] }}</strong> won the toss and choose to
                            <strong>{{ $match_data[0]['first_innings'] }}</strong>
                        </p>
                        @if($scores['second']['runs'] !=null && $scores['second']['overs'] !=null )
                            <p class="inactive-team">
                                <strong>{{ $scores['basic']['winner_team'] }}</strong> won by
                                <strong>{{ $scores['basic']['win_digit'] }}</strong> {{ $scores['basic']['win_by'] }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            {{--First Innings--}}
            <ul class="innings-toggle">
                <li :class="{ 'selected-innings':first_inn,'first':true }" @click="showFirst"><a>1st innings</a>
                    <div class="arrow-down" v-if="first_inn"></div>
                </li>
                @if($scores['second']['runs'] !=null || $scores['second']['overs'] !=null)
                    <li :class="{ 'selected-innings':second_inn, 'second':true }" @click="showSecond"><a>2nd innings</a>
                        <div class="arrow-down" v-if="second_inn"></div>
                    </li>
                @endif
            </ul>
            <div class="first-innings" v-if="first_inn">
                {{--<p class="innings-no">1st Innings</p>--}}
                <div class="batting-table">
                    <p class="table-name">Batting: {{ $scores['basic']['batting_team'] }} </p>
                    <table>
                        <thead>
                        <th>Name(Jrs. No)</th>
                        <th>R</th>
                        <th>B</th>
                        <th>SR</th>
                        </thead>
                        @foreach($scores['basic']['first_bats'] as $batsman)
                            @foreach($scores['first']['consumed'] as $player)
                                @if($batsman->player_id==$player->id)
                                    <tr>
                                        <td>{{ $batsman->player_name }}({{ $batsman->jersey }})
                                            <span style="font-size: 10pt;font-style: italic">
                                                @if($player->w_taker != "")
                                                    @foreach($scores['basic']['first_bowls'] as $bowler)
                                                        @if($bowler->player_id == $player->w_taker)
                                                            {{ $player->out }}{{ $bowler->player_name }}
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </span>
                                        </td>
                                        <td>{{ $player->run }}</td>
                                        <td>{{ $player->ball }}</td>
                                        <td>
                                            @if($player->ball>0)
                                                {{ round(($player->run/$player->ball)*100,2) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach

                    </table>
                </div>
                <br>
                <div class="recent-notifications">
                    <p class="extra-runs" style="font-size: 16pt">
                    <span>
                            Total Extra= {{ $scores['first']['extras'] }} runs
                    </span>
                    </p>
                </div>
                <div class="bowling-table">
                    <p class="table-name">Bowling: {{ $scores['basic']['bowling_team'] }}</p>
                    <table>
                        <thead>
                        <th>Name(Jrs. No)</th>
                        <th>Ov</th>
                        <th>R</th>
                        <th>W</th>
                        <th>ECO.</th>
                        </thead>
                        @foreach($scores['basic']['first_bowls'] as $bowler)
                            @foreach($scores['first']['consumed'] as $player)
                                @if($bowler->player_id==$player->id)
                                    <tr>
                                        <td>{{ $bowler->player_name }}({{ $bowler->jersey }})</td>
                                        <td>{{ (int)($player->ball/6).".".($player->ball)%6 }}</td>
                                        <td>{{ $player->run }}</td>
                                        <td>
                                            <?php
                                            $wicket = 0;
                                            foreach ($scores['first']['consumed'] as $person) {
                                                if ($person->w_taker == $player->id) {
                                                    $wicket++;
                                                }
                                            }
                                            ?>
                                            {{ $wicket }}
                                        </td>
                                        <td>
                                            @if($player->ball>0)
                                                {{ round(($player->run/$player->ball)*6,2) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </table>
                </div>
            </div>
            {{--Second Innings--}}
            @if($scores['second']['runs'] !=null || $scores['second']['overs'] !=null )
                <div class="second-innings" v-if="second_inn">
                    {{--<p class="innings-no">2nd Innings</p>--}}
                    <div class="batting-table">
                        <p class="table-name">Batting: {{ $scores['basic']['bowling_team'] }} </p>
                        <table>
                            <thead>
                            <th>Name(Jrs. No)</th>
                            <th>R</th>
                            <th>B</th>
                            <th>SR</th>
                            </thead>
                            @foreach($scores['basic']['first_bowls'] as $batsman)
                                @foreach($scores['second']['consumed'] as $player)
                                    @if($batsman->player_id==$player->id)
                                        <tr>
                                            <td>
                                                {{ $batsman->player_name }}({{ $batsman->jersey }})
                                                <span style="font-size: 10pt;font-style: italic">
                                                @if($player->w_taker != "")
                                                        @foreach($scores['basic']['first_bats'] as $bowler)
                                                            @if($bowler->player_id == $player->w_taker)
                                                                {{ $player->out }}{{ $bowler->player_name }}
                                                            @endif
                                                        @endforeach
                                                    @endif
                                            </span>
                                            </td>
                                            <td>{{ $player->run }}</td>
                                            <td>{{ $player->ball }}</td>
                                            <td>
                                                @if($player->ball>0)
                                                    {{ round(($player->run/$player->ball)*100,2) }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach

                        </table>
                    </div>
                    <br>
                    <div class="recent-notifications">
                        <p class="extra-runs" style="font-size: 16pt">
                    <span>
                            Total Extra= {{ $scores['second']['extras'] }} runs
                    </span>
                        </p>
                    </div>
                    <div class="bowling-table">
                        <p class="table-name">Bowling: {{ $scores['basic']['batting_team'] }}</p>
                        <table>
                            <thead>
                            <th>Name(Jrs. No)</th>
                            <th>Ov</th>
                            <th>R</th>
                            <th>W</th>
                            <th>ECO.</th>
                            </thead>
                            @foreach($scores['basic']['first_bats'] as $bowler)
                                @foreach($scores['second']['consumed'] as $player)
                                    @if($bowler->player_id==$player->id)
                                        <tr>
                                            <td>{{ $bowler->player_name }}({{ $bowler->jersey }})</td>
                                            <td>{{ (int)($player->ball/6).".".($player->ball)%6 }}</td>
                                            <td>{{ $player->run }}</td>
                                            <td>
                                                <?php
                                                $wicket = 0;
                                                foreach ($scores['second']['consumed'] as $person) {
                                                    if ($person->w_taker == $player->id) {
                                                        $wicket++;
                                                    }
                                                }
                                                ?>
                                                {{ $wicket }}
                                            </td>
                                            <td>
                                                @if($player->ball>0)
                                                    {{ round(($player->run/$player->ball)*6,2) }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </table>
                    </div>
                </div>
            @endif
        </section>
    </div>
    <script src="{{ mix('/js/scoreboard.js') }}"></script>
@endsection
