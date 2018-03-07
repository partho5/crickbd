@extends('base_layout')


@section('page_content')

    {{--CSS--}}
    <link rel="stylesheet" href="/assets/css/add_players.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    {{--Fonts--}}
    <link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">


    <div class="col-md-12" id="main-body" style="margin-top: 50px;">
        <form action="">
            <div class="col-md-6">
                <p class="team-name">EEE</p>
                <div class="col-md-12">
                    <label for="" class="col-md-4">Player 1</label>
                    <div class="col-md-8">
                        <input name="name" type="text" class="form-control" placeholder="Mamun Or Rashid" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <p class="team-name">EEE</p>
                <div class="col-md-12">
                    <label for="" class="col-md-4">Player 1</label>
                    <div class="col-md-8">
                        <input name="name" type="text" class="form-control" placeholder="Mamun Or Rashid" required>
                    </div>
                </div>
            </div>

                <div class="col-md-12">
                    <div class="col-md-2 col-md-offset-5">
                        <input type="submit" class="btn btn-success" value="Finish Up">
                    </div>
                </div>
        </form>
    </div>


@endsection