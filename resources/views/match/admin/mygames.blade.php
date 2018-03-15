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

            <div id="upcoming-match">Upcoming</div>

            <a href="">
                <div class="col-md-12 match-detail-wrap">
                    <div class="col-md-8">
                        <div>
                            <p class="team-name">EEE <span style="color: #636b6f;">vs</span> CSE</p>
                            <p><span class="over">20 </span>overs match</p>
                            <p>Venue: <span class="venue"> Dhaka University Central Field</span></p>
                            <p >Starts From <span class="start-date-time">11 MArch,2018@ 4:00 PM</span></p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-top: 10%">
                        <button class="btn btn-info">View</button>
                        <button class="btn btn-success">Start</button>
                        <button class="btn btn-danger">Edit</button>
                    </div>
                </div>

            </a>


            <div id="recent-match">Completed</div>

            <a href="">
                <div class="col-md-12 match-detail-wrap">
                    <div class="col-md-8">
                        <div>
                            <p class="team-name">EEE <span style="color: #636b6f;">vs</span> CSE</p>
                            <p class="result">EEE won by 5 wicket or 23 run</p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-top: 10%">
                        <button class="btn btn-success">View</button>
                    </div>
                </div>
            </a>

        </section>
    </div>
    {{--</div>--}}

@endsection