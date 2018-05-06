@extends('base_layout')
@section('page_content') {{--CSS--}}
<link href="/assets/css/add_players.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
{{--Fonts--}}
<link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<div class="col-md-12" id="main-body" style="margin-top: 50px;padding-bottom: 400px;">
    <form action="/match/{{Request::route('id')}}/addplayer" method="post">
        {{ csrf_field() }}
        <div class="col-md-6">
            <p class="team-name">
                {{ $match->teams[0]->team_name }}
            </p>
            @for ($i=1;$i
<=$match->player_total;$i++)
                <div class="col-md-12 from-group">
                    <label class="col-md-3" for="p_t1_{{$i}}">
                        Player {{ $i }}
                    </label>
                    <div class="col-md-6">
                        <input class="form-control" name="p_t1_{{$i}}" placeholder="nickname" required=""
                               type="text">
                        </input>
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" name="jersey_t1_{{$i}}" placeholder="jersey(opt)"
                               type="text">
                        </input>
                    </div>
                </div>
            @endfor
        </div>
        <div class="col-md-6">
            <p class="team-name">
                {{ $match->teams[1]->team_name }}
            </p>
            @for ($i=1;$i<=$match->player_total;$i++)
                <div class="col-md-12">
                    <label class="col-md-3" for="p_t2_{{$i}}">
                        Player {{ $i }}
                    </label>
                    <div class="col-md-6">
                        <input class="form-control" name="p_t2_{{$i}}" placeholder="nickname" required=""
                               type="text">
                        </input>
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" name="jersey_t2_{{$i}}" placeholder="jersey(opt)"
                               type="text">
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
</div>
@endsection
