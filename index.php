<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="public/css/style.css"/>
    <title>Weather</title>
</head>
<body>

<div id="target">
    <div class="section-widget-one">
        <div class="current-city"></div>
    </div>
    <div class="section-widget-two">
        <div class="location-city"></div>
    </div>
</div>

<script type="text/template" id="template">
    <div class="container-widget-chisinau">
        <div class="layout-widget">
            <div class="container-top">
                <div class="section-left">
                    <div class="city-name"><%= city %></div>
                </div>
                <div class="section-center">
                    <div class="icon-weather">
                        <img src=<%= iconUrl %>
                        alt=""/>
                    </div>
                </div>
                <div class="section-right">
                    <div class="temperature"><%= temp %>&#176;С</div>
                </div>
            </div>
            <div class="container-bottom">
                <div class="data-source">
                    <a href="/statistic.php">weather last 7 days</a>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="template-location">
    <div class="container-widget-location">
        <div class="layout-widget-location">
            <div class="container-top-location">
                <div class="section-left-location">
                    <div class="city-name-location"><%= cityLoc %>, <%= countryLoc %></div>
                </div>
                <div class="section-center-location">
                    <div class="icon-weather-location">
                        <img src=<%= iconUrlLoc %>
                        alt=""/>
                    </div>
                </div>
                <div class="section-right-location">
                    <div class="temperature-location"><%= tempLoc %>&#176;С</div>
                </div>
            </div>
        </div>
    </div>
</script>
<script src="public/js/libs/jquery.js"></script>
<script src="public/js/libs/underscore.js"></script>
<script src="public/js/libs/moment.js"></script>
<script src="public/js/main.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        renderWidget();
    });
</script>
</body>
</html>


