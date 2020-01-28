<?php
//File input. Name it as text.text
$EGGinput = fopen("data.txt", "r");

//declear vars
$arr = [];
$timeStamp = [];
$deviceOneReadings = [];
$deviceTwoReadings = [];
$averageTime;
$timeStamps;
$averageDeviceOneValue;
$averageDeviceTwoValue;

if ($EGGinput) {
    while (($line = fgets($EGGinput)) !== false) {
        $line = preg_replace('/\s+/', ' ', $line);
        $s = explode(' ', trim($line));
        array_push($arr, $s);
    }

    for ($x = 0; $x < count($arr); $x++) {
        array_push($timeStamp, $arr[$x][0]);
        array_push($deviceOneReadings, $arr[$x][1]);
        array_push($deviceTwoReadings, $arr[$x][2]);
    }

    $averageTime = array_sum($timeStamp) / count($timeStamp);
    $averageDeviceOneValue = array_sum($deviceOneReadings) / count($deviceOneReadings);
    $averageDeviceTwoValue = array_sum($deviceTwoReadings) / count($deviceTwoReadings);
    $timeStamps = count($timeStamp);

    //echo ($deviceTwoReadings[78999]);

    fclose($EGGinput);

} else {
    die("Error: The EGG data does not exist.");
}
?>


<!--Ui Design work-->
<!--DISPLAY: Summary Stat & 1 Graph (Combination) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Brainly - Expesicor</title>
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
</head>
<body>

<h1>Brainly by Expesicor.</h1>
<h3>Average TimeStamp : <small style="color: grey"><?php echo ($averageTime) ?></small></h3>
<h3>Time Stamps Recorded : <small style="color: grey"><?php echo ($timeStamps) ?></small></h3>
<h3>Average EGG value - Device 1 : <small style="color: grey"><?php echo ($averageDeviceOneValue) ?></small></h3>
<h3>Average EGG value - Device 2 <small style="color: grey"><?php echo ($averageDeviceTwoValue) ?></small></h3>

  <div id="chart_div"></div>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <script>
  google.charts.load('current', {packages: ['corechart', 'line']});
  google.charts.setOnLoadCallback(drawLogScales);

function drawLogScales() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'X');
      data.addColumn('number', 'Electrode A');
      data.addColumn('number', 'Electrode B');


      data.addRows([

        <?php

$x = 0;
while ($x < count($arr)) {
    echo "[" . $deviceOneReadings[$x] . ", " . $deviceTwoReadings[$x] . ", " . intval($timeStamp[$x]) . "],";
    $x++;

}

?>



      ]);

      var options = {
        height: 450,
        hAxis: {
          title: 'Electrodes Reading',
          logScale: true
        },
        vAxis: {
          title: 'Time Stamp',
          logScale: true
        },
        colors: ['#a52714', '#2388BC']
      };

      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }

  </script>
</body>
</html>
