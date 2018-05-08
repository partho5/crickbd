@extends('base_layout')


@section('page_content')

    {{--CSS--}}
    <link rel="stylesheet" href="/assets/css/create.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    {{--Fonts--}}
    <link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

    <div class="col-md-12" style="padding: 0;">
        <div class="col-md-8 col-md-offset-2">
            <section id="main-body" style="margin-top: 52px;">
                <p class="team-name">Edit Match</p>
                @if(!empty($edit_msg) && $edit_msg=='success')
                    <h5>
                        Match has been edited successfully.
                    </h5>
                @else

                    <form action="/mygames/edit/{{ $old_match_data[0]->match_id }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="input-group col-md-12">
                                <label for="" class="col-md-4">Team 1 Name</label>
                                <div class="col-md-8">
                                    <input name="team1" value="{{ $old_match_data[0]->teams[0]->team_name }}" type="text" class="form-control"
                                           placeholder="Enter team 1 name" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group col-md-12">
                                <label for="" class="col-md-4">Team 2 Name</label>
                                <div class="col-md-8">
                                    <input name="team2" value="{{ $old_match_data[0]->teams[1]->team_name }}" type="text" class="form-control"
                                           placeholder="Enter team 2 name" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group col-md-12">
                                <label for="" class="col-md-4">Date Time</label>
                                <div class="col-md-8">
                                    <input name="match_time" value="{{ $old_match_data[0]->start_time }}" type="datetime-local"
                                           class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group col-md-12">
                                <label for="" class="col-md-4">Location</label>
                                <div class="col-md-8">
                                <textarea name="location" id="msg" class="form-control" rows="3"
                                          placeholder="Ex. Dhaka University Central Field">{{ $old_match_data[0]->location }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group col-md-12">
                                <label for="" class="col-md-4">Total Over</label>
                                <div class="col-md-8">
                                    <input name="total_over" type="number" value="{{ $old_match_data[0]->over }}"
                                           class="form-control" placeholder="Total Over" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group col-md-12">
                                <label for="total_player" class="col-md-4">Player Per Team</label>
                                <div class="col-md-8">
                                    <input name="total_player" id="total_player" value="{{ $old_match_data[0]->player_total }}"
                                           type="number" class="form-control" placeholder="Enter total player" required>
                                </div>
                            </div>
                        </div>


                        {{--Extra Player Add , Do some backend work here--}}


                        {{--<div class="form-group">
                            <div class="input-group col-md-12">
                                <label for="" class="col-md-4">Extra Player Per Team</label>
                                <div class="col-md-8">
                                    <input name="" id="" value="" type="number" class="form-control"
                                           placeholder="Enter extra player">
                                </div>
                            </div>
                        </div>--}}


                        <div class="form-group">
                            <div class="input-group col-md-12">
                                <div class="col-md-4 col-md-offset-4">
                                    <input type="submit" name="submit_match" class="btn btn-success" value="Update">
                                </div>
                            </div>
                        </div>
                    </form>

                @endif

            </section>
        </div>
    </div>


@endsection