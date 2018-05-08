@extends('base_layout')


@section('page_content')

    {{--CSS--}}
    <link rel="stylesheet" href="/assets/css/homepage.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    {{--Fonts--}}
    <link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">



    {{--<div class="col-md-12">--}}
    <div class="col-md-8 col-md-offset-2" style="padding: 0;">
        <section id="main-body" style="margin-top: 50px;padding-bottom: 200px;">
            @if(count($matches['today'][0])>=1)
                <div id="today-match">Running Matches</div>
                @foreach($matches['today'][0] as $today_match)
                    <?php
                    $date_ = strtotime($today_match->start_time);
                    $match_day = date('D, jS M, Y@ h:i A', $date_);
                    ?>
                    <a href="/details/{{ $today_match->match_id }}">
                        <div class="match-detail-wrap col-md-12">
                            <div class="col-md-7">
                                <p class="team-name">{{ $today_match->teams[0]->team_name }} <span
                                            style="color: #636b6f;">vs</span> {{ $today_match->teams[1]->team_name }}
                                </p>
                                @if($today_match->second)
                                    <?php
                                    list($whole, $decimal) = explode('.', $today_match['s_overs']);
                                    ?>
                                    <p class="team-active">{{ $today_match['s_team'] }} <span
                                                class="run-active">{{ $today_match['s_runs'] }}</span>/<span
                                                class="wicket">{{ $today_match['s_wickets'] }}</span>
                                        <span
                                                class="active-over"> ({{ $today_match['s_overs'] }} over)</span></p>
                                    <p class="inactive-team">{{ $today_match['f_team'] }} {{ $today_match['f_runs'] }}
                                        /{{ $today_match['f_wickets'] }} ({{ $today_match['f_overs'] }} over)</p>
                                    <p class="inactive-team">{{ $today_match['s_team'] }} need <span
                                                class="run-active">{{ $today_match['f_runs']-$today_match['s_runs']+1 }}</span>
                                        runs in
                                        <span class="ball-left">{{ ($today_match->over*6)-($whole*6+$decimal) }}</span>
                                        balls
                                    </p>
                                @else
                                    <p class="team-active">{{ $today_match['f_team'] }} <span
                                                class="run-active">{{ $today_match['f_runs'] }}</span>/<span
                                                class="wicket">{{ $today_match['f_wickets'] }}</span>
                                        <span
                                                class="active-over"> ({{ $today_match['f_overs'] }} over)</span></p>
                                    <p class="inactive-team">{{ $today_match['toss_winner'] }} won the toss and choose
                                        to
                                        <span
                                                class="run-active">{{ $today_match->first_innings }}</span>
                                    </p>
                                @endif

                                <p><span class="over">{{ $today_match->over }} </span>overs match</p>
                            </div>

                            <div class="col-md-5">

                                <p>Venue: <span class="venue"> {{ $today_match->location }}</span></p>
                                <a href="/matchpanel/{{ $today_match->match_id }}"><button class="btn btn-success">GO</button></a>
                                <a href="/mygames/edit/{{ $today_match->match_id }}"><button class="btn btn-danger">Edit</button></a>
                            </div>


                        </div>

                    </a>
                @endforeach
            @endif

            @if(count($matches['upcoming'][0])>=1)
                <div id="upcoming-match">Upcoming Match</div>
                @foreach($matches['upcoming'][0] as $up_match)
                    <?php
                    $date_ = strtotime($up_match->start_time);
                    $match_day = date('D, jS M, Y@ h:i A', $date_);
                    ?>
                    <a href="/view/{{ $up_match->match_id }}">
                        <div class="match-detail-wrap col-md-12">
                            <div class="col-md-7">
                                <h2 class="team-name">{{ $up_match->teams[0]->team_name }} <span
                                            style="color: #636b6f;">vs</span> {{ $up_match->teams[1]->team_name }}</h2>
                                <p><span class="over">{{ $up_match->over }} </span>overs match</p>
                                <p>Venue: <span class="venue"> {{ $up_match->location }}</span></p>
                                <p>Starts From <span class="start-date-time">{{ $match_day }}</span></p>
                            </div>
                            <a href="/view/{{ $up_match->match_id }}">
                                <button class="btn btn-info">View</button>
                            </a>
                            <a href="/matchpanel/{{ $up_match->match_id }}">
                                <button class="btn btn-success">Start</button>
                            </a>
                            <a href="/mygames/edit/{{ $up_match->match_id }}">
                                <button class="btn btn-danger">Edit</button>
                            </a>
                            <a href="/mygames/edit_players/{{ $up_match->match_id }}">
                                <button class="btn btn-danger">Update Players</button>
                            </a>

                        </div>
                    </a>
                @endforeach
            @endif

            {{--Delayed MAtch Start--}}
            @if(count($matches['delayed'][0])>=1)
                <div id="delayed-match">Delayed Matches</div>
                @foreach($matches['delayed'][0] as $delay)
                    <?php
                    $date_ = strtotime($delay->start_time);
                    $match_day = date('D, jS M, Y@ h:i A', $date_);
                    ?>
                    <a>
                        <div class="match-detail-wrap col-md-12">
                            <div class="col-md-7">
                                <div>
                                    <h2 class="team-name">{{ $delay->teams[0]->team_name }}<span
                                                style="color: #636b6f;"> vs </span>{{ $delay->teams[1]->team_name }}
                                    </h2>
                                    <p><span class="over"> {{ $delay->over }}</span> overs match</p>
                                    <p>Venue: <span class="venue">{{ $delay->location }}</span></p>
                                    <p>Starts From <span class="start-date-time">{{ $match_day }}</span></p>
                                </div>
                            </div>
                            <div class="col-md-5" style="margin-top: 6%">
                                <a href="/view/{{ $delay->match_id }}">
                                    <button class="btn btn-info">View</button>
                                </a>
                                <a href="/matchpanel/{{ $delay->match_id }}">
                                    <button class="btn btn-success">Start</button>
                                </a>
                                <a href="/mygames/edit/{{ $delay->match_id }}">
                                    <button class="btn btn-danger">Edit</button>
                                </a>
                                <a href="/mygames/edit_players/{{ $delay->match_id }}">
                                    <button class="btn btn-danger">Update Players</button>
                                </a>
                            </div>

                        </div>

                    </a>
                @endforeach
            @endif

            {{--Delayed match End--}}

            @if(count($matches['complete'][0])>=1)
                <div id="recent-match">Recent Matches</div>
                @foreach($matches['complete'][0] as $com_match)
                    <a href="/scoreboard/{{$com_match->match_id}}">
                        <div class="match-detail-wrap col-md-12">
                            <div class="col-md-7">
                                <h2 class="team-name">{{ $com_match->teams[0]->team_name }} <span
                                            style="color: #636b6f;">vs</span> {{$com_match->teams[1]->team_name}}
                                </h2>
                                <p class="result">{{ $com_match->winner_team }} won
                                    by {{ $com_match->win_digit }} {{ $com_match->win_by }}</p>
                            </div>
                            <div class="col-md-5" style="margin-top: 2%">
                                <a href="">
                                    <button class="btn btn-info">View</button>
                                </a>
                            </div>
                        </div>
                    </a>
                @endforeach

            @endif

        </section>
    </div>
    {{--</div>--}}

@endsection