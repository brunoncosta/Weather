# Weather API

## Live

* [brunoncosta.com](http://weather.brunoncosta.com/)

## Built With

* PHP
* IP Data from [brunoncosta.com](http://api.brunoncosta.com/ip/) ([IPInfo](https://ipinfo.io/))
* Weather Data from [OpenWeatherMap](http://api.openweathermap.org)

## config.php
```php
return [
   "weather" => [
      "url" => [
         "current"  => "http://api.openweathermap.org/data/2.5/weather",
         "forecast" => "http://api.openweathermap.org/data/2.5/forecast"
      ],
      "key" => "YOUT_KEY",
      "directions" => [
         'N',
         'NNE',
         'NE',
         'ENE',
         'E',
         'ESE',
         'SE',
         'SSE',
         'S',
         'SSW',
         'SW',
         'WSW',
         'W',
         'WNW',
         'NW',
         'NNW',
         'N2'
      ]
   ]
];
```

## Routes

```
/current
/humidity
/sky
/temperature
/wind
/forecast
/cardinal

```

## GET Request
Available on /current, /humidity, /sky, /temperature, /wind and /forecast.

```
?location=Lisbon&units=metric
```
or
```
?ip=YOUT_IP&units=metric
```

## Response Data
```
location
temperature
fells_like
min_temperature
max_temperature
humidity
wind_speed
wind_speed_km
wind_direction
sky
sky_description
clouds
```

## Authors
* **Bruno Costa**
