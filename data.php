<?php
header('Content-Type:application/json; charset=UTF-8');

$key = "07c2af2d54a0478c9ad72529172703";

//============ Weather data query for current city ===========//
function getWeatherLocation($key, $location) {
    $urlLocation = "http://api.worldweatheronline.com/premium/v1/weather.ashx?key=$key&q=$location&format=json&fx=no";
    if(!$dataLocation = file_get_contents($urlLocation)){
        return false;
    }
    global $cityLoc;
    if($_GET['lat'] && $_GET['lon']){
        $lat = $_GET['lat'];
        $lon = $_GET['lon'];
        $cityLoc = getCityByCoordinates($lat, $lon);
    }
    $taskListLocation = json_decode(($dataLocation));
    //$cityLoc = $taskListLocation->data->request[0]->query;
    $temp = $taskListLocation->data->current_condition[0]->temp_C;
    $time = $taskListLocation->data->current_condition[0]->observation_time;
    $iconUrl = $taskListLocation->data->current_condition[0]->weatherIconUrl[0]->value;
    $resultDataLocation = array(
        'city' => $cityLoc,
        'temp' => $temp,
        'time' => $time,
        'iconUrl' => $iconUrl
    );
    return $resultDataLocation;
}

//============= Define the name of the city in which we are located ===========//
function getCityByCoordinates($lat, $lon){
    $urlCityName = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lon&key=AIzaSyBRFq4rBAD6EfGhMLLNxSrqSWGJ3MW7gIk";
    if(!$nameCity = file_get_contents($urlCityName)){
        return FALSE;
    }
    $dataCurrentCity = json_decode(($nameCity));
//    $cityLocation = $dataCurrentCity->results->address_components->formatted_address[5]->long_name;
//    $countryLocation = $dataCurrentCity->results->address_components->formatted_address[6]->long_name;

    $cityLocation = $dataCurrentCity->results[0]->address_components[4]->long_name;
    $countryLocation = $dataCurrentCity->results[0]->address_components[5]->long_name;
    $resultCityLocation = array(
       'cityLoc'     => $cityLocation,
       'countryLoc' => $countryLocation
    );
    return $resultCityLocation;

}

//============ Weather forecast for Chisinau ===========//
function getWeatherData($key, $city) {
//    if($data = apc_fetch('widget_'.$location)) {
//        return $data;
//    }


    $url = "http://api.worldweatheronline.com/premium/v1/weather.ashx?key=$key&q=$city&format=json&fx=no";
    if(!$data = file_get_contents($url)) {
        return FALSE;
    }
    $taskList = json_decode($data);
    $city = $taskList->data->request[0]->query;
    $temp = $taskList->data->current_condition[0]->temp_C;
    $time = $taskList->data->current_condition[0]->observation_time;
    $iconUrl = $taskList->data->current_condition[0]->weatherIconUrl[0]->value;
    $result = array(
        'city' => $city,
        'temp' => $temp,
        'time' => $time,
        'iconUrl' => $iconUrl
    );

//    apc_add('widget_'.$location , $result, 60 * 60);

    return $result;
}

//============ Request data for statistics for a certain period ===========//
function getWeatherStats($key, $city, $date, $endDate) {
    $urlStatistic = "http://api.worldweatheronline.com/premium/v1/past-weather.ashx?key=$key&q=$city&format=json&date=$date&enddate=$endDate";
    if(!$dataStatistic = file_get_contents($urlStatistic)) {
        return false;
    }
    $taskListStatistic = json_decode($dataStatistic);
    $listDays = $taskListStatistic->data->weather;
    $listDataDay = [];
    foreach ($listDays as $day ) {
        $listDataDay[] = array(
            'dateDay' => $day->date,
            'maxTempC' => $day->maxtempC,
            'minTempC' => $day->mintempC,
            'iconUrlDay' => $day->hourly[5]->weatherIconUrl[0]->value
        );
    }
    return $listDataDay;
}
$action = isset($_GET['action']) ? $_GET['action'] : 'widget';

if($action === 'widget') {

    if(!$data = getWeatherData($key, 'chisinau')) {
        echo json_encode(array(
                'success' => FALSE,
                'message' => 'Could not retrieve data',
            )
        );
        exit;
    }
    if($_GET['lat'] && $_GET['lon']){
        $lat = $_GET['lat'];
        $lon = $_GET['lon'];
        $cityLoc = $lat .','. $lon;
        if(!$dataLocation = getWeatherLocation($key, $cityLoc)) {

            echo json_encode(array(
                    'success' => FALSE,
                    'message' => 'Could not retrieve data',
                )
            );
            exit;
        }
    }
    echo json_encode(array(
            'success'      => TRUE,
            'data'         => $data,
            'dataLocation' => $dataLocation
        )
    );
    exit;
}
if($action === 'stats') {
    $datePeriod = new DateTime('-1 days');
    $endDate = $datePeriod->format('Y-m-d');
    $datePeriod = new DateTime('-7 days');
    $startDate = $datePeriod->format('Y-m-d');

    if(isset($_GET['dateStart']) && isset($_GET['dateEnd']) && !empty($_GET['dateStart']) && !empty($_GET['dateEnd'])) {
        $endDate = $_GET['dateEnd'];
        $startDate = $_GET['dateStart'];
    }

    if(!$data = getWeatherStats($key, 'chisinau', $startDate, $endDate)) {
        echo json_encode(array(
                'success' => false,
                'message' => 'Could not retrieve stats',
            )
        );
        exit;
    }

    echo json_encode(array(
            'success' => TRUE,
            'data' => $data,
            'period' => array(
                'dateStart' => $startDate,
                'dateEnd' => $endDate
            )
        )
    );
    exit;
}

