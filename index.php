<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Weather App</h1>
        <div class="search">
            <form method="post">
                <input type="text" name="city" placeholder="Enter city name">
                <button type="submit">Search</button>
            </form>
        </div>
    </header>

    <main>
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $city = $_POST["city"];
            $api_key = "dbee818759d3efa0fed12f6ae9f14f21"; // Replace with your actual API key
            $api_url = "https://api.openweathermap.org/data/2.5/forecast?q=$city&appid=$api_key";

            $response = file_get_contents($api_url);
            $data = json_decode($response, true);

            if ($data["cod"] === "200") {
                echo '<div class="weather-card">';
                echo "<h2 class='city'>Weather forecast for $city</h2>";

                $dates = array();
                foreach ($data["list"] as $forecast) {
                    $date = date("Y-m-d", $forecast["dt"]);
                    if (!in_array($date, $dates)) {
                        $day_name = date("l", $forecast["dt"]);
                        $icon = $forecast["weather"][0]["icon"];
                        $temperature = round($forecast["main"]["temp"] - 273.15);
                        $description = $forecast["weather"][0]["description"];
                        $humidity = $forecast["main"]["humidity"];
                        $wind_speed = $forecast["wind"]["speed"];

                        echo "<div class='day'>";
                        echo "<h3>$day_name ($date)</h3>";
                        echo "<div class='icon'><img src='http://openweathermap.org/img/wn/$icon.png' alt='$description'></div>";
                        echo "<div class='temperature'>$temperature&deg;C</div>";
                        echo "<div class='description'>$description</div>";
                        echo "<div class='details'>";
                        echo "<div>Humidity: $humidity%</div>";
                        echo "<div>Wind: $wind_speed km/h</div>";
                        echo "</div>";
                        echo "</div>";

                        $dates[] = $date;
                    }
                }

                echo '</div>';
            } else {
                echo '<p class="error">City not found. Please try again.</p>';
            }
        }
        ?>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Weather App</p>
    </footer>
</body>
</html>
