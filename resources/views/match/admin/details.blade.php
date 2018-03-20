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
                <p class="team-name">EEE <span style="color: #636b6f;">vs</span> CSE</p>
                <div>
                    <div class="match-detail-wrap">
                        <p><span class="over">20 </span>overs match</p>
                        <p>Venue: <span class="venue"> Dhaka University Central Field</span></p>
                        <p >Starts From <span class="start-date-time">11 MArch,2018@ 4:00 PM</span></p>
                    </div>
                </div>
            </div>
            <div class="batting-table">
                <p class="table-name">Team 2</p>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Jersey No</th>
                    </tr>
                    <tr>
                        <td>Sourav</td>
                        <td>54</td>
                    </tr>
                    <tr class="playing">
                        <td>Sourav</td>
                        <td>54</td>
                    </tr>
                    <tr class="out">
                        <td>Sourav Kumar Pramanik</td>
                        <td>54</td>
                    </tr>
                    <tr>
                        <td>Sourav</td>
                        <td>54</td>
                    </tr>
                    <tr>
                        <td>Sourav</td>
                        <td>54</td>
                    </tr>
                    <tr>
                        <td>Sourav</td>
                        <td>65</td>
                    </tr>
                </table>
            </div>
            <br>



            <div class="bowling-table">
                <p class="table-name">Team 2</p>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Jersey No</th>
                    </tr>
                    <tr>
                        <td>Sourav</td>
                        <td>54</td>
                    </tr>
                    <tr class="playing">
                        <td>Sourav</td>
                        <td>54</td>
                    </tr>
                    <tr class="out">
                        <td>Sourav Kumar Pramanik</td>
                        <td>54</td>
                    </tr>
                    <tr>
                        <td>Sourav</td>
                        <td>54</td>
                    </tr>
                    <tr>
                        <td>Sourav</td>
                        <td>54</td>
                    </tr>
                    <tr>
                        <td>Sourav</td>
                        <td>65</td>
                    </tr>
                </table>
            </div>
        </section>


    </div>



@endsection