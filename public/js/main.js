

//=============== Display of 2 blocks with the weather ==========//
window.renderWidget = function () {

//=============== Get the data for Chisinau ===============//
    $.get("/data.php?action=widget&lat=55.755826&lon=37.6173",
        function (result) {
            // Rendering widget
            var obj = {
                city: result['data']['city'],
                temp: result['data']['temp'],
                iconUrl: result['data']['iconUrl']
            };
            var tmpl = _.template($('#template').html());
            $('#target .current-city').html(tmpl(obj));
        }
    );
//============== Receive data for the current city ==============//
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var latitude  = position.coords.latitude;
            var longitude = position.coords.longitude;
            //50.4501, 30.5234
            //data.php?action=widget&lat="+latitude+"&lon="+longitude
            $.get("/data.php?action=widget&lat=50.4501&lon=30.5234",
                function (result) {
                    // Rendering widget
                    var objLoc = {
                        cityLoc: result['dataLocation']['city']['cityLoc'],
                        countryLoc: result['dataLocation']['city']['countryLoc'],
                        tempLoc: result['dataLocation']['temp'],
                        iconUrlLoc: result['dataLocation']['iconUrl']
                    };
                    var tmplLoc = _.template($('#template-location').html());
                    $('#target .location-city').html(tmplLoc(objLoc));
                }
            );
        });
    }
    else {
        alert("Your browser does not support georeferencing");
    }
};

//========== DatePicker output ===========//
window.renderStats = function () {
    var templPicker = _.template($('#container-datepicker').html());
    $('#statistic-table .picker').html(templPicker);

    var formatDateStart = null;
    var formatDateEnd = null;
    var dateFormat = "mm/dd/yy",

    from = $("#from")
        .datepicker({
            numberOfMonths: 1,
            dateFormat: 'dd-mm-yy'
        })
    .on("change", function () {
        to.datepicker("option", "minDate", getDate(this, dateFormat));
        var currentDateStart = $("#from").datepicker("getDate");
        formatDateStart = moment(currentDateStart).format('YYYY/MM/DD');
    }),
    to = $("#to").datepicker({
        numberOfMonths: 1,
        dateFormat: 'dd-mm-yy'
    })
    .on("change", function () {
        from.datepicker("option", "maxDate", getDate(this, dateFormat));
        var currentDateEnd = $("#to").datepicker("getDate");
        formatDateEnd = moment(currentDateEnd).format('YYYY/MM/DD');
    });

    getStatsData(null, null);

    $('.btn').click(function () {
        getStatsData(formatDateStart, formatDateEnd)
    });
};

//=============== Render weather statistics for a certain period ===============//
function getStatsData(formatDateStart, formatDateEnd) {
    $.ajax({
        type: "GET",
        url: '/data.php?action=stats',
        dataType: 'json',
        data: {
            dateStart: formatDateStart,
            dateEnd: formatDateEnd
        },
        success: function (response) {
            renderStatsTemplate(response)
        }
    });
}

//================= Weather statistics and graph ===============//
function renderStatsTemplate(response) {
    $('#statistic-table .stats').html('');
    var dayWeek = [];
    var minTemperature = [];
    var maxTemperature = [];
    $.each(response['data'], function (index, value) {
        var day = moment(value['dateDay']).format('dd DD MMM');
        var min = value['minTempC'];
        var max = value['maxTempC'];
        dayWeek.push(day);
        minTemperature.push(min);
        maxTemperature.push(max);
        var obj = {
            minTemp: value['minTempC'],
            dateDay: moment(value['dateDay']).format('dd DD MMM'),
            maxTemp: value['maxTempC'],
            iconUrlDay: value['iconUrlDay']
        };
        var tmpl = _.template($('#template-statistic').html());
        $('#statistic-table .stats').append(tmpl(obj));
    });

    var period = {
        dateStart: moment(response['period']['dateStart']).format('DD MMM, YYYY'),
        dateEnd: moment(response['period']['dateEnd']).format('DD MMM, YYYY')
    };
    var tmplPeriod = _.template($('#template-period').html());
    $('#statistic-table .period').html(tmplPeriod(period));


    var data = {
        labels: dayWeek,
        series: [
            minTemperature,
            maxTemperature
        ]
    };
    var options = {
        width: 800,
        height: 200
    };
    new Chartist.Line('.ct-chart', data, options);

}


function getDate(element, dateFormat) {
    var date;
    try {
        date = $.datepicker.parseDate(dateFormat, element.value);
    } catch (error) {
        date = null;
    }
    return date;
}