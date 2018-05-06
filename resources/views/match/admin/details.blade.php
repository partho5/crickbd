@extends('base_layout')


@section('page_content')
    {{--CSS--}}
    <link rel="stylesheet" href="/assets/css/admin-details.css">
    <link rel="stylesheet" href="/assets/css/common-style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    {{--Fonts--}}
    <link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">


    <div class="col-md-8 col-md-offset-2" style="padding: 0;">
        <section id="main-body" style="margin-top: 50px;">
            <div id="today-match">
                <p class="team-name">{{ $match['data'][0]->teams[0]->team_name }} <span
                            style="color: #636b6f;">vs</span> {{ $match['data'][0]->teams[1]->team_name }}</p>
                <div>
                    <div class="match-detail-wrap">
                        <p><span class="over">{{ $match['data'][0]->over }} </span>overs match</p>
                        <p>Venue: <span class="venue"> {{ $match['data'][0]->location }}</span></p>
                        <?php
                            $date = strtotime($match['data'][0]->start_time);
                            $match_day = date('D, jS M, Y@ h:i A', $date);
                        ?>
                        <p>Starts From <span class="start-date-time"> {{ $match_day }}</span></p>
                    </div>
                </div>
            </div>
            @if(count($match['players']['team1'])>0 && count($match['players']['team2'])>0)
                <div class="batting-table">
                    <p class="table-name">{{ $match['data'][0]->teams[0]->team_name }}</p>
                    <table>
                        <thead>
                        <th>Name</th>
                        <th>Jersey No</th>
                        </thead>
                        @foreach($match['players']['team1'] as $player)
                            <tr>
                                <td>{{ $player->player_name }}</td>
                                <td>{{ $player->jersey }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <br>


                <div class="bowling-table">
                    <p class="table-name">{{ $match['data'][0]->teams[1]->team_name }}</p>
                    <table>
                        <thead>
                        <th>Name</th>
                        <th>Jersey No</th>
                        </thead>
                        @foreach($match['players']['team2'] as $player)
                            <tr>
                                <td>{{ $player->player_name }}</td>
                                <td>{{ $player->jersey }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection