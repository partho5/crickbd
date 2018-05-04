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
                    {{ $match_data[0]['teams'][0]['team_name'] }}
                    <span style="color: #636b6f;">vs</span> {{ $match_data[0]['teams'][1]['team_name'] }}
                </p>
                <div>
                    <div class="match-detail-wrap">
                        <p class="team-active">{{ $match_data[0]['teams'][0]['team_name'] }} <span class="run-active"> @{{ total_run }}</span>/<span
                                    class="wicket">@{{ countWicket }}</span> <span class="active-over"> (@{{ ball_data.current_over }}.@{{ ball_data.current_ball }} over)</span>
                        </p>
                        <p class="inactive-team" v-if="!isSecInn && checkToss">
                            <strong>{{ $scores['basic']['toss_winner_team_name'] }}</strong> won the toss and choose to
                            <strong>{{ $match_data[0]['first_innings'] }}</strong>
                        </p>
                    </div>
                </div>
            </div>
            {{--First Innings--}}
            <ul class="innings-toggle">
                <li class="first"><a href="">1st innings</a></li>
                <li class="second"><a href="">2nd innings</a></li>
            </ul>
            <div class="first-innings">
                <p class="innings-no">1st Innings</p>
                <div class="batting-table">
                    <p class="table-name">Batting: {{ $scores['basic']['batting_team'] }} </p>
                    <table>
                        <thead>
                        <th>Name(Jersey No)</th>
                        <th>Run(s)</th>
                        <th>Ball(s)</th>
                        </thead>
                        @foreach($scores['first']['bats'] as $batsman)
                            <tr>
                                <td>{{ $batsman->player_name }}</td>
                                <td>{{ $batsman->cum_run }}</td>
                                <td>{{ $batsman->total_ball }}</td>
                            </tr>
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
                            <th>Name(Jersey No)</th>
                            <th>Over(s)</th>
                            <th>Run</th>
                            <th>Wicket(s)</th>
                        </thead>
                        @foreach($scores['first']['bowls'] as $bowler)
                            <tr>
                                <td>{{ $bowler->player_name }}</td>
                                <td>{{ $bowler->total_ball }}</td>
                                <td>{{ $bowler->total_run }}</td>
                                <td>{{ $bowler->wickets }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            {{--Second Innings--}}
            @if($scores['second']['runs'] !=null || $scores['second']['overs'] !=null )
                <div class="second-innings">
                    <p class="innings-no">2nd Innings</p>
                    <div class="batting-table">
                        <p class="table-name">Batting: {{ $scores['basic']['bowling_team'] }} </p>
                        <table>
                            <thead>
                            <th>Name(Jersey No)</th>
                            <th>Run(s)</th>
                            <th>Ball(s)</th>
                            </thead>
                            @foreach($scores['second']['bats'] as $batsman)
                                <tr>
                                    <td>{{ $batsman->player_name }}</td>
                                    <td>{{ $batsman->cum_run }}</td>
                                    <td>{{ $batsman->total_ball }}</td>
                                </tr>
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
                            <th>Name(Jersey No)</th>
                            <th>Over(s)</th>
                            <th>Run</th>
                            <th>Wicket(s)</th>
                            </thead>
                            @foreach($scores['second']['bowls'] as $bowler)
                                <tr>
                                    <td>{{ $bowler->player_name }}</td>
                                    <td>{{ $bowler->total_ball }}</td>
                                    <td>{{ $bowler->total_run }}</td>
                                    <td>{{ $bowler->wickets }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            @endif
        </section>
        <pre>
            {{--{{ print_r($match_data[0]['teams'][1]['team_name']) }}--}}
            {{ print_r($scores['basic']['bowling_team']) }}
            {{--{{ print_r($match_data[0]['first_innings']) }}--}}
        </pre>
    </div>
@endsection
