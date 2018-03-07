@extends('base_layout')


@section('page_content')

    {{--CSS--}}
    <link rel="stylesheet" href="/assets/css/create.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    {{--Fonts--}}
    <link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">


    <div class="col-md-8 col-md-offset-2">
        <section id="main-body" style="margin-top: 52px;">
            <form action="" method="post" enctype="">
                <p class="team-name">Create Match</p>
                {{--<input type="hidden" name="_method" value="PATCH">--}}
                {{--<input type="hidden" name="_token" value="nuVs0eMF8SZbEe5741UAcRrcxV4h7Ov6mbHu7LV0">--}}
                <div class="form-group">
                    <div class="input-group col-md-12">
                        <label for="" class="col-md-4">Team 1 Name</label>
                        <div class="col-md-8">
                            <input name="name" type="text" class="form-control" value="Mamun Or Rashid" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group col-md-12">
                        <label for="" class="col-md-4">Team 2 Name</label>
                        <div class="col-md-8">
                            <input name="name" type="text" class="form-control" value="Mamun Or Rashid" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group col-md-12">
                        <label for="" class="col-md-4">Date Time</label>
                        <div class="col-md-8">
                            <input name="name" type="date" class="form-control" value="Mamun Or Rashid" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group col-md-12">
                        <label for="" class="col-md-4">Location</label>
                        <div class="col-md-8">
                            <textarea name="" id="msg" class="form-control" rows="3" placeholder="Ex. Dhaka University Central Field" ></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group col-md-12">
                        <label for="" class="col-md-4">Total Over</label>
                        <div class="col-md-8">
                            <input name="name" type="number" class="form-control" value="Mamun Or Rashid" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group col-md-12">
                        <label for="" class="col-md-4">Player Per Team</label>
                        <div class="col-md-8">
                            <input name="name" type="number" class="form-control" value="Mamun Or Rashid" required>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <div class="input-group col-md-12">
                        <div class="col-md-4 col-md-offset-4">
                            <input type="submit" class="btn btn-success" value="Create Match">
                        </div>
                    </div>
                </div>
            </form>

        </section>
    </div>
@endsection
