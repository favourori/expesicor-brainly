<?php
//File input. Name must be data.txt
$EEGInput = fopen("data.txt", "r");

//declear variables
$arr = []; //main array that holds every extracted data from data.txt
$timeStamp = []; //Holds the timestamps
$electrodeOneReadings = []; //Holds values from device 1
$electrodeTwoReadings = []; //Holds values from device 2

/* 
the number of times a reading was done - in this case 79,000
*/
$timeStamps;

/* 
sum of device 1 values / number of values (Average)
*/
$averageDeviceOneValue; 

/* 
sum of device 2 values / number of values  (Average)
*/
$averageDeviceTwoValue;

//Check if data exists & extract data into 3 arrays (1. Timestamp, electrodeOne, Electrode2)
if ($EEGInput) {
    while (($line = fgets($EEGInput)) !== false) {
        $line = preg_replace('/\s+/', ' ', $line);
        $s = explode(' ', trim($line));
        array_push($arr, $s);
    }

    for ($x = 0; $x < count($arr); $x++) {
        array_push($timeStamp, $arr[$x][0]);
        array_push($electrodeOneReadings, $arr[$x][1]);
        array_push($electrodeTwoReadings, $arr[$x][2]);
    }
   //Loop through file and extract data
    $averageDeviceOneValue = array_sum($electrodeOneReadings) / count($electrodeOneReadings);
    $averageDeviceTwoValue = array_sum($electrodeTwoReadings) / count($electrodeTwoReadings);
    $timeStamps = count($timeStamp);

    fclose($EEGInput);

} else {
    die("Error: The EGG data does not exist.");
}
?>

<!--Ui Design work-->
<!--DISPLAY: Summary Stat(s) & 2 Graphs (Electrode 1 & 2) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Brainly - Expesicor</title>
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <style>
    .showLoader{
        display: block;
    }
    </style>
</head>
<body>

<!--Navbar-->
<div>
<nav style="background-color: #125FAD " class="z-depth-0">
    <div class="nav-wrapper container">
      <a href="#" class="brand-logo">Brainly<span style="font-size: 40px; color: #81C341">.</span> <small style="font-size: 13px; font-weight: 200">From Expesicor</small> </a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li><a href="https://github.com/favourori/expesicor-brainly">About</a></li>
        <li><a href="https://github.com/favourori/expesicor-brainly">How to use</a></li>
      </ul>
    </div>
  </nav>
</div>

<!--Dashboard-->
<div class="container" style="margin-top:70px">
<h1 style="font-size: 20px; font-weight: 300; letter-spacing: 2px; color: grey">SUMMARY</h1>
<div class="row">
<!--Time Recording-->
      <div class="col s12 m4">
         <div class="card white z-depth-1">
            <div class="card-content">
            <span class="card-title" style="color: #2097D4;">Timestamp Recorded</span>
          <p style="font-size: 18px; font-weight: 200"> <?php echo ($timeStamps) ?></>
        </div>
      </div>
<!--Avg Device 1-->
      </div>
      <div class="col s12 m4">
       <div class="card white">
        <div class="card-content">
          <span class="card-title" style="color:#2097D4">Avg. Electrode A (value) </span>
          <p style="font-size: 18px; font-weight: 200"> <?php echo ($averageDeviceOneValue) ?></p>
        </div>
       </div>
<!--Avg Device 2-->
      </div>
      <div class="col s12 m4">
        <div class="card white">
        <div class="card-content">
          <span class="card-title" style="color:#2097D4">Avg. Electrode B (value) </span>
          <p style="font-size: 18px; font-weight: 200"> <?php echo ($averageDeviceTwoValue) ?></p>
        </div>
          </div>
      </div>
    </div>
</div>

<div style=" text-align: center;" class="showLoader container" id="loader">
<img src="images/loading.gif" width="60">

</div>
<!--Graph section-->

<!--Graph For Device 1 against timestamp-->
  <div id="chart_div" style="height: 510px"></div>
<!--Graph For device 2 against Timestamp-->
  <div id="chart_div2" style="height: 510px"></div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
  google.charts.load('current', {packages: ['corechart', 'line']});
  google.charts.setOnLoadCallback(drawCurveTypes);
function drawCurveTypes() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'X');
      data.addColumn('number', 'Electrode A');

//Looping & adding data to the graph
      data.addRows([
<?php
$x = 0;
while ($x < count($arr)) {
    echo "[" . $electrodeOneReadings[$x] . ", " . intval($timeStamp[$x]) . "],";
    $x++;
}?>
      ]);

 var options = {
        hAxis: {
          title: 'Device Reading'
        },
        vAxis: {
          title: 'TimeStamp (seconds)'
        },
        series: {
          1: {curveType: 'function'}
        }
      };

      var chart1 = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart1.draw(data, options);
    }

//Graph 2
google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawCurveTypes2);

function drawCurveTypes2() {

      var data = new google.visualization.DataTable();
      data.addColumn('number', 'X');
      data.addColumn('number', 'Electrode B');
      data.addRows([
<?php
$x = 0;
while ($x < count($arr)) {
    echo "[" . $electrodeTwoReadings[$x] . ", " . intval($timeStamp[$x]) . "],";
    $x++;
}?>]);

      var options = {
        hAxis: {
          title: 'Device Reading'
        },
        vAxis: {
          title: 'TimeStamp (seconds)'
        },
        series: {
          1: {curveType: 'function'}
        }
       
      };

var chart2 = new google.visualization.LineChart(document.getElementById('chart_div2'));
chart2.draw(data, options);
    }
   /*
   if this were to be an async req, 
   I would have hidden the loader gif on recieveing the data
   */
   setTimeout(() => {
    document.getElementById("loader").style.display = "none";
   }, 3000);

  </script>
   <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>


<!--Thank you very much ☺️-->
