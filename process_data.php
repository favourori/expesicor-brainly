<?php
/*
File input. File name must be data.txt & must be placed at the root dir
*/
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

    //Loop through file and extract data
    for ($x = 0; $x < count($arr); $x++) {
        array_push($timeStamp, $arr[$x][0]);
        array_push($electrodeOneReadings, $arr[$x][1]);
        array_push($electrodeTwoReadings, $arr[$x][2]);
    }
   //calculating average
    $averageDeviceOneValue = array_sum($electrodeOneReadings) / count($electrodeOneReadings);
    $averageDeviceTwoValue = array_sum($electrodeTwoReadings) / count($electrodeTwoReadings);
    $timeStamps = count($timeStamp);

    fclose($EEGInput);

} else {
  //Throws error if data.txt is not found
    die("Error: The EGG data does not exist.");
}
?>