<link rel="stylesheet" href="/assets/css/decide_winner.css">
<div class="final-result-wrapper">
    <form class="final-result text-center">
        <p class="text-center final-result-heading">Final Result</p>
        <div class="result-details-wrap">
            <p class="team-name" style="font-size: 25pt;">@{{ match_data.teams[0].team_name }} <span
                        style="color: #636b6f;">vs</span> @{{
                match_data.teams[1].team_name }}</p>
            <p class="won-by">
                <span style="font-style: italic;font-size: 10pt;">@{{ tossWinnerTeam }} won the toss and choose to @{{ match_data.first_innings }}</span><br><br>
                <span>@{{ fieldingTeam }}: @{{ first_innings.total_first }}/@{{ first_innings.first_inn_wicket}} (@{{ first_innings.first_inn_over }} overs)</span><br>
                <span>@{{ battingTeam }}: @{{ total_run }}/@{{ countWicket }} (@{{ ball_data.current_over }}.@{{ ball_data.current_ball }} overs)</span><br><br>
            <div style="font-size: 15pt;">
                <span>@{{ winner.winning_team_name }} won by </span> @{{ winner.win_digit }}</span> <span>@{{ winner.win_by }}</span>
            </div>
            </p>
            <p><span class="over">@{{ match_data.over }} </span>overs match</p>
            <p>Venue: <span class="venue"> @{{ match_data.location }}</span></p>
            <p>Started at <span class="start-date-time"> @{{ match_data.start_time | localDateTime }}</span></p>
            <a :href='"/scoreboard/"+match_id'>Full details</a>
        </div>
    </form>
</div>