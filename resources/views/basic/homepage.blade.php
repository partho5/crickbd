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
                    $match_day = date('l, jS F, Y@ h:i A', $date_);
                    ?>
                        <a href="/details/{{ $today_match->match_id }}">
                            <div class="match-detail-wrap">
                                <p class="team-name">{{ $today_match->teams[0]->team_name }} <span
                                            style="color: #636b6f;">vs</span> {{ $today_match->teams[1]->team_name }}
                                </p>
                                <p class="team-active">EEE <span class="run-active">125</span>/<span
                                            class="wicket">6</span>
                                    <span
                                            class="active-over"> (5.2 over)</span></p>
                                <p class="inactive-team">Karachi Kings 225/6 (20 over)</p>
                                <p class="inactive-team">Islamabad United need <span class="run-active">100</span> runs
                                    in
                                    <span
                                            class="ball-left">59</span> balls</p>
                            </div>

                            <div class="match-detail-wrap">
                                <h2 class="team-name">{{ $today_match->teams[0]->team_name }} <span
                                            style="color: #636b6f;">vs</span> {{ $today_match->teams[1]->team_name }}</h2>
                                <p><span class="over">{{ $today_match->over }} </span>overs match</p>
                                <p>Venue: <span class="venue"> {{ $today_match->location }}</span></p>
                                <p>Starts From <span class="start-date-time">{{ $match_day }}</span></p>
                            </div>

                        </a>
                @endforeach
            @endif

            @if(count($matches['upcoming'][0])>=1)
                <div id="upcoming-match">Upcoming Match</div>
                @foreach($matches['upcoming'][0] as $up_match)
                    <?php
                    $date_ = strtotime($up_match->start_time);
                    $match_day = date('l, jS F, Y@ h:i A', $date_);
                    ?>
                    <a href="/view/{{ $up_match->match_id }}">
                        <div class="match-detail-wrap">
                            <h2 class="team-name">{{ $up_match->teams[0]->team_name }} <span
                                        style="color: #636b6f;">vs</span> {{ $up_match->teams[1]->team_name }}</h2>
                            <p><span class="over">{{ $up_match->over }} </span>overs match</p>
                            <p>Venue: <span class="venue"> {{ $up_match->location }}</span></p>
                            <p>Starts From <span class="start-date-time">{{ $match_day }}</span></p>
                        </div>
                    </a>
                @endforeach
            @endif

                {{--Delayed MAtch Start--}}
                <div id="delayed-match">Delayed</div>

                <a href="">
                    <div class="match-detail-wrap">
                        <div>
                            <h2 class="team-name">sbhxjxb<span style="color: #636b6f;">vs</span></h2>
                            <p><span class="over"> </span>overs match</p>
                            <p>Venue: <span class="venue"></span></p>
                            <p >Starts From <span class="start-date-time"></span></p>
                        </div>

                        {{--<div class="col-md-4" style="margin-top: 10%">--}}
                        {{--<a href="/matchpanel/view/1"><button class="btn btn-info">View</button></a>--}}
                        {{--<button class="btn btn-success">Start</button>--}}
                        {{--<button class="btn btn-danger">Edit</button>--}}
                        {{--</div>--}}
                    </div>

                </a>

                {{--Delayed match End--}}

            @if(count($matches['complete'][0])>=1)
                <div id="recent-match">Recent Matches</div>
                @foreach($matches['complete'][0] as $com_match)
                    <a href="/scoreboard/{{$com_match->match_id}}">
                        <div class="match-detail-wrap">
                            <p class="team-name">{{ $com_match->teams[0]->team_name }} <span
                                        style="color: #636b6f;">vs</span> {{$com_match->teams[1]->team_name}}
                            </p>
                            <p class="result">ACCE won by 4 wicket</p>
                        </div>
                    </a>
                @endforeach

            @endif

        </section>
    </div>
    {{--</div>--}}

@endsection