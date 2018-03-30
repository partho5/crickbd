@extends('base_layout')


@section('page_content')
    {{--CSS--}}
    <link rel="stylesheet" href="/assets/css/admin-details.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    {{--Fonts--}}
    <link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">


    <div class="col-md-8 col-md-offset-2" style="padding: 0;">
        <section id="main-body" style="margin-top: 50px;">
            <div id="today-match">
                <p class="team-name">EEE <span style="color: #636b6f;">vs</span> ACCE</p>
                <div>
                    <div class="match-detail-wrap">
                        <p><span class="over">20 </span>overs match</p>
                        <p>Venue: <span class="venue"> Dhaka University Central Field</span></p>
                        <p >Starts From <span class="start-date-time">31 March,2018@ 12:00 PM</span></p>
                    </div>
                </div>
            </div>
            <div class="batting-table">
                <p class="table-name">EEE</p>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Jersey No</th>
                    </tr>
                    <tr>
                        <td>Seeam</td>
                        <td>00</td>
                    </tr>
                    {{--<tr class="playing">--}}
                        {{--<td>Shakil</td>--}}
                        {{--<td>26</td>--}}
                    {{--</tr>--}}
                    {{--<tr class="out">--}}
                        {{--<td>Emon</td>--}}
                        {{--<td>54</td>--}}
                    {{--</tr>--}}
                    <tr>
                        <td>Shakil</td>
                        <td>26</td>
                    </tr>
                    <tr>
                        <td>Emon</td>
                        <td>11</td>
                    </tr>
                    <tr>
                        <td>Muntasir</td>
                        <td>17</td>
                    </tr>
                    <tr>
                        <td>Sabuj</td>
                        <td>44</td>
                    </tr>
                    <tr>
                        <td>Riyad</td>
                        <td>12</td>
                    </tr>
                    <tr>
                        <td>Sayfullah</td>
                        <td>8</td>
                    </tr>
                    <tr>
                        <td>Shuvo</td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td>Saiful</td>
                        <td>N/A</td>
                    </tr>
                    <tr>
                        <td>Krisna</td>
                        <td>13</td>
                    </tr>
                    <tr>
                        <td>Hridoy</td>
                        <td>N/A</td>
                    </tr>
                </table>
            </div>
            <br>



            <div class="bowling-table">
                <p class="table-name">ACCE</p>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Jersey No</th>
                    </tr>
                    {{--<tr>--}}
                        {{--<td>Shakil</td>--}}
                        {{--<td>26</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td>Emon</td>--}}
                        {{--<td>11</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td>Muntasir</td>--}}
                        {{--<td>17</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td>Sabuj</td>--}}
                        {{--<td>44</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td>Riyad</td>--}}
                        {{--<td>12</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td>Sayfullah</td>--}}
                        {{--<td>8</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td>Shuvo</td>--}}
                        {{--<td>4</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td>Saiful</td>--}}
                        {{--<td>N/A</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td>Krisna</td>--}}
                        {{--<td>13</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td>Hridoy</td>--}}
                        {{--<td>N/A</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td>Seeam</td>--}}
                        {{--<td>00</td>--}}
                    {{--</tr>--}}
                </table>
            </div>
        </section>
        {{--<div class="col-md-12">--}}
            {{--<div class="col-md-2 col-md-offset-5">--}}
                {{--<button class="btn btn-success">Start Now</button>--}}
            {{--</div>--}}
        {{--</div>--}}


    </div>



@endsection