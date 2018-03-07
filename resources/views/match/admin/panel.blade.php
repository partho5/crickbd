@extends('base_layout')


@section('page_content')
    {{--CSS--}}
    <link rel="stylesheet" href="/assets/css/panel.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    {{--Fonts--}}
    <link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">


    <div class="col-md-8 col-md-offset-2">

        {{--Score Board --}}

        <div id="body-head" style="margin-top: 50px;">
            <div id="today-match">
                <p class="team-name">EEE <span style="color: #636b6f;">vs</span> CSE</p>
                <div>
                    <div class="match-detail-wrap">
                        <p class="team-active">EEE <span class="run-active">125</span>/<span class="wicket">6</span> <span class="active-over"> (5.2 over)</span></p>
                        <p class="inactive-team">Karachi Kings 225/6 (20 over)</p>
                        <p class="inactive-team">Islamabad United need <span class="run-active">100</span> runs in <span class="ball-left">59</span> balls</p>
                     </div>
                </div>
            </div>
        </div>

        {{--Score Board End--}}


        {{--Select Team To Bat--}}


        <div class="send-to-bat">
            <div class="col-md-12">
                <div class="col-md-6">
                    <p class="choose-to-bat btn-assigner">
                        Team 1 <span><button class="btn set-to-bat-btn" value="2" >Send to Bat</button></span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="btn-assigner">
                        Team 2 <span><button class="btn set-to-bat-btn" value="2" >Batting</button></span>
                    </p>
                </div>
            </div>
        </div>



        {{--End Select Team To Bat--}}



        {{--Run Table--}}


        <div class="run-table">
            <p class="table-name">Run Table <span title="Undo Last Event"><button>Undo</button></span></p>

            <div class="add-run">
                <div class="col-md-12">
                    <div class="col-md-1 btn-assigner">Add</div>
                    <div class="col-md-11">
                        <button class="btn zero" value="0">0</button>
                        <button class="btn one"  value="1">1</button>
                        <button class="btn two" value="2">2</button>
                        <button class="btn three" value="3">3</button>
                        <button class="btn four" value="4">4</button>
                        <button class="btn six" value="6">6</button>
                        <button class="btn default" value="0">Extra Run?</button>
                        <select name="" id="out">
                            <option selected disabled>Out</option>
                            <option value="">Bowled</option>
                            <option value="">RunOut</option>
                            <option value="">CatchOut</option>
                        </select>

                    </div>
                </div>
            </div>
            <div class="add-run">
                <div class="col-md-12">
                    <div class="col-md-1 btn-assigner">Add </div>
                    <div class="col-md-11">
                        <span class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">No Ball
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <button class="btn zero" value="0">+0</button>
                        <button class="btn one"  value="1">+1</button>
                        <button class="btn two" value="2">+2</button>
                        <button class="btn three" value="3">+3</button>
                        <button class="btn four" value="4">+4</button>
                        <button class="btn six" value="6">+6</button>
                            </ul>
                        </span>

                        <span class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">By
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <button class="btn zero" value="0">0</button>
                                <button class="btn one"  value="1">1</button>
                                <button class="btn two" value="2">2</button>
                                <button class="btn three" value="3">3</button>
                                <button class="btn four" value="4">4</button>
                                <button class="btn six" value="6">6</button>
                            </ul>
                        </span>


                        <span class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Wide
                            </button>
                                {{--<span class="caret"></span></button>--}}
                            {{--<ul class="dropdown-menu">--}}
                                {{--<button class="btn zero" value="0">+by 0</button>--}}
                                {{--<button class="btn one"  value="1">+by 1</button>--}}
                                {{--<button class="btn two" value="2">+by 2</button>--}}
                                {{--<button class="btn three" value="3">+by 3</button>--}}
                                {{--<button class="btn four" value="4">+by 4</button>--}}
                                {{--<button class="btn six" value="6">+by 6</button>--}}
                            {{--</ul>--}}
                        </span>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <button class="btn two" value="2" >End Session</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{--Run table End--}}


        {{--Batting table--}}

        <div class="batting-table">
            <p class="table-name">Bating Table</p>
            <table>
                <tr>
                    <th>Name(Jersey No)</th>
                    <th>Run(s)</th>
                    <th>Ball(s)</th>
                    <th>Status</th>
                </tr>

                <tr>
                    <td>Sourav</td>
                    <td>54</td>
                    <td>65</td>
                    <td>
                        <span class="dropdown">
                            <button class="btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Send
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <button class="btn zero" value="0">On-Strike</button>
                                <button class="btn one"  value="1">Non-strike</button>
                            </ul>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Sourav</td>
                    <td>54</td>
                    <td>65</td>
                    <td>
                        <span class="dropdown">
                            <button class="btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Send
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <button class="btn zero" value="0">On-Strike</button>
                                <button class="btn one"  value="1">Non-strike</button>
                            </ul>
                        </span>
                    </td>
                </tr>
                <tr class="playing">
                    <td>Sourav</td>
                    <td>54</td>
                    <td>65</td>
                    <td>
                        <span class="dropdown">
                            <button class="btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Send
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <button class="btn zero" value="0">On-Strike</button>
                                <button class="btn one"  value="1">Non-strike</button>
                            </ul>
                        </span>
                    </td>
                </tr>
                <tr class="out">
                    <td>Sourav Kumar Pramanik</td>
                    <td>54</td>
                    <td>65</td>
                    <td>
                        <span class="dropdown">
                            <button class="btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Send
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <button class="btn zero" value="0">On-Strike</button>
                                <button class="btn one"  value="1">Non-strike</button>
                            </ul>
                        </span>
                    </td>
                </tr>
            </table>
        </div>

     {{--Batting Table End--}}


        {{--Bowling Table--}}


        <div class="bowling-table">
            <p class="table-name">Bowling Table</p>
            <table>
                <tr>
                    <th>Name(Jersey No)</th>
                    <th>Over(s)</th>
                    <th>Run(s)</th>
                    <th>Status</th>
                </tr>

                <tr>
                    <td>Sourav</td>
                    <td>54</td>
                    <td>65</td>
                    <td><button class="status-btn" >Active</button></td>
                </tr>
                <tr>
                    <td>Sourav</td>
                    <td>54</td>
                    <td>65</td>
                    <td><button class="status-btn" >Active</button></td>
                </tr>
                <tr class="playing">
                    <td>Sourav</td>
                    <td>54</td>
                    <td>65</td>
                    <td><button class="status-btn" >Active</button></td>
                </tr>
                <tr class="out">
                    <td>Sourav Kumar Pramanik</td>
                    <td>54</td>
                    <td>65</td>
                    <td><button class="status-btn" >Active</button></td>
                </tr>
                <tr>
                    <td>Sourav</td>
                    <td>54</td>
                    <td>65</td>
                    <td><button class="status-btn" >Active</button></td>
                </tr>
                <tr>
                    <td>Sourav</td>
                    <td>54</td>
                    <td>65</td>
                    <td><button class="status-btn" >Active</button></td>
                </tr>
                <tr>
                    <td>Sourav</td>
                    <td>54</td>
                    <td>65</td>
                    <td><button class="status-btn" >Active</button></td>
                </tr>
            </table>
        </div>


        {{--Bowling Table End--}}


        <p class="table-name">Extra Runs</p>
        <div class="add-run">
            <div class="col-md-12">
                <div class="recent-notifications">
                    <p class="extra-runs">1 3 2 3 1 ..... = 23 Total</p>
                    <br><br>
                    <h3>Recent Activities: </h3>
                    <p>Partneership: 25 Runs from 16 ball(s)</p>
                    <p>Recent Balls: 0 2 0 1 0 6 | 0 1 0 </p>
                </div>
            </div>
        </div>



    </div>
@endsection