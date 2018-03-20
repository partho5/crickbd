@extends('base_layout')


@section('page_content')

    {{--CSS--}}
    <link rel="stylesheet" href="/assets/css/mygames.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    {{--Fonts--}}
    <link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">



    {{--<div class="col-md-12">--}}
    <div class="col-md-8 col-md-offset-2"style="padding: 0;">
        <section id="main-body" style="margin-top: 50px;">
            <div id="today-match">Running</div>
            <a href="/match">
                <div class="col-md-12 match-detail-wrap"">
                    <div class="col-md-8">
                        <div>
                            <p class="team-name">EEE <span style="color: #636b6f;">vs</span> CSE</p>
                            <p class="team-active">EEE <span class="run-active">125</span>/<span class="wicket">6</span> <span class="active-over"> (5.2 over)</span></p>
                            <p class="inactive-team">Karachi Kings 225/6 (20 over)</p>
                            <p class="inactive-team">Islamabad United need <span class="run-active">100</span> runs in <span class="ball-left">59</span> balls</p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-top: 10%">
                        <button class="btn btn-success">GO</button>
                        <button class="btn btn-danger">Edit</button>
                    </div>
                </div>
            </a>

            {{--Delayed MAtch Start--}}
    <div id="delayed-match">Delayed</div>

            <a href="">
                <div class="col-md-12 match-detail-wrap">
                    <div class="col-md-8">
                        <div>
                            <p class="team-name">sbhxjxb<span style="color: #636b6f;">vs</span></p>
                            <p><span class="over"> </span>overs match</p>
                            <p>Venue: <span class="venue"></span></p>
                            <p >Starts From <span class="start-date-time"></span></p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-top: 10%">
                        <a href="/matchpanel/view/1"><button class="btn btn-info">View</button></a>
                        <button class="btn btn-success">Start</button>
                        <button class="btn btn-danger">Edit</button>
                    </div>
                </div>

            </a>

    {{--Delayed match End--}}



            <div id="upcoming-match">Upcoming</div>

            @if(sizeof($data[0]['upcoming'])>=1)
                @foreach( $data[0]['upcoming'] as $upmatch)
                    <a href="/matchpanel/{{ $upmatch['match_id'] }}">
                        <div class="col-md-12 match-detail-wrap">
                            <div class="col-md-8">
                                <div>
                                    <p class="team-name"> {{ $upmatch['teams'][0]['team_name'] }} <span style="color: #636b6f;">vs</span> {{ $upmatch['teams'][1]['team_name'] }}</p>
                                    <p><span class="over">{{ $upmatch['over'] }} </span>overs match</p>
                                    <p>Venue: <span class="venue"> {{ $upmatch['location'] }}</span></p>
                                    <p >Starts From <span class="start-date-time">{{ $upmatch['start_time'] }}</span></p>
                                </div>
                            </div>
                            <div class="col-md-4" style="margin-top: 10%">
                                <a href="/matchpanel/view/1"><button class="btn btn-info">View</button></a>
                                <button class="btn btn-success">Start</button>
                                <button class="btn btn-danger">Edit</button>
                            </div>
                        </div>

                    </a>
                @endforeach
            @else
                <h4>
                    No Upcoming Matches Available
                </h4>
            @endif


    <div id="completed-match">Completed</div>

    <a href="">
        <div class="col-md-12 match-detail-wrap">
            <div class="col-md-8">
                <div>
                    <p class="team-name">EEE <span style="color: #636b6f;">vs</span> CSE</p>
                    <p class="result">EEE won by 5 wicket or 23 run</p>
                </div>
            </div>
            <div class="col-md-4" style="margin-top: 10%">
                <a href="/matchpanel/view/1"><button class="btn btn-info">View</button></a>
            </div>
        </div>

    </a>

        </section>
    </div>
    {{--</div>--}}

@endsection