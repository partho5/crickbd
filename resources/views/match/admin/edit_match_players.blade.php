@extends('base_layout')


@section('page_content')

    {{--CSS--}}
    <link href="/assets/css/add_players.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    {{--Fonts--}}
    <link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <div class="col-md-12" id="main-body" style="margin-top: 50px;padding-bottom: 400px;">
        <h3>Edit Players</h3>
        @if(!empty($edit_msg) && $edit_msg=='success')
            <h5>
                Player info has been updated successfully.
            </h5>
        @else
            <form action="/mygames/edit_players/{{ $match->match_id }}" method="post">
                {{ csrf_field() }}
                <div class="col-md-6">
                    <p class="team-name">
                        {{ $old_players[0]->team_name }}
                    </p>
                    @for ($i=1;$i<=$match->player_total;$i++)
                        <div class="col-md-12">
                            <label class="col-md-4" for="p_{{ $old_players[0]->players[$i-1]->player_id }}">
                                Player {{ $i }}
                            </label>
                            <div class="col-md-8">
                                <input class="form-control" name="p_{{ $old_players[0]->players[$i-1]->player_id }}"
                                       placeholder="Ex. Sakib Al Hasan" required=""
                                       type="text" value="{{ $old_players[0]->players[$i-1]->player_name }}">
                                </input>
                                <input class="form-control" name="p_j_{{ $old_players[0]->players[$i-1]->player_id }}"
                                       placeholder="jersey number"
                                       type="number" value="{{ $old_players[0]->players[$i-1]->jersey }}">
                                </input>
                            </div>
                        </div>
                    @endfor
                </div>
                <div class="col-md-6">
                    <p class="team-name">
                        {{ $old_players[1]->team_name }}
                    </p>
                    @for ($i=1;$i<=$match->player_total;$i++)
                        <div class="col-md-12">
                            <label class="col-md-4" for="p_{{ $old_players[0]->players[$i-1]->player_id }}">
                                Player {{ $i }}
                            </label>
                            <div class="col-md-8">
                                <input class="form-control" name="p_{{ $old_players[1]->players[$i-1]->player_id }}"
                                       placeholder="Ex. Sakib Al Hasan" required=""
                                       type="text" value="{{ $old_players[1]->players[$i-1]->player_name }}">
                                </input>
                                <input class="form-control" name="p_j_{{ $old_players[1]->players[$i-1]->player_id }}"
                                       placeholder="jersey number"
                                       type="number" value="{{ $old_players[1]->players[$i-1]->jersey }}">
                                </input>
                            </div>
                        </div>
                    @endfor
                </div>
                <br>
                <div class="col-md-12">
                    <div class="col-md-2 col-md-offset-5">
                        <input class="btn btn-success" type="submit" value="Finish Up">
                        </input>
                    </div>
                </div>
                </br>
            </form>
        @endif
    </div>


@endsection