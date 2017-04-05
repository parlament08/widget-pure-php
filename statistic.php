<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="public/css/style.css"/>
    <link rel="stylesheet" href="public/js/jquery-ui/jquery-ui.css"/>
    <link rel="stylesheet" href="public/js/chartist/dist/chartist.min.css"/>
    <title>Weather</title>
</head>
<body>

<div id="statistic-table">
    <div class="period"></div>
    <div class="picker"></div>
    <div class="stats"></div>
</div>
<div class="ct-chart ct-perfect-fourth"></div>

<script type="text/template" id="template-period">
    <h4>Statistics weather period: <%= dateStart %> - <%= dateEnd %></h4>
</script>

<script type="text/template" id="template-statistic">
    <div class="section-day">
        <div class="table-statistic">
            <div class="table-col">
                <div class="date"><%= dateDay %></div>
                <div class="weather-icon">
                    <img src="<%= iconUrlDay %>" alt=""/>

                    <div class="temp">
                        <div class="min-temp">min: <%= minTemp %></div>
                        <div class="max-temp">max: <%= maxTemp %></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="container-datepicker">
    <div class="datePicker-wrapper">
        <label for="from">From</label>
        <input type="text" id="from" name="from">
        <label for="to">to</label>
        <input type="text" id="to" name="to">
        <input class="btn" type="button" value="show">
    </div>
</script>

<script src="public/js/libs/jquery.js"></script>
<script src="public/js/jquery-ui/jquery-ui.js"></script>
<script src="public/js/libs/underscore.js"></script>
<script src="public/js/chartist/dist/chartist.min.js"></script>
<script src="public/js/libs/moment.js"></script>
<script src="public/js/main.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        renderStats();
    });
</script>
</body>
</html>


