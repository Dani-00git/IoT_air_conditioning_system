<?php
    
    $action = $_GET['action'];

    switch($action) {

        case "upload" : 
            uploadData();
        break;
        case "download" :
            downloadData();
        break; 
        case "setCommands" :
            setCommands();
        break; 
        case "getCommands" :
            getCommands();
        break; 
        case "getReading" :
            getReading();
        break; 
	}

function downloadData() {
    //$mydate=getdate();
    //$day = $mydate[mday]-1;
    //$t = "$mydate[year]-$day-$mydate[mon] $mydate[hours]:$mydate[minutes]:$mydate[seconds]";
    $query_string = 'SELECT * FROM sensor_readings ORDER BY time DESC'; 
    //WHERE time>"'.$t.'"
    
    $link = mysqli_connect('localhost','PPM','localhost') or die('Cannot connect to the DB');
    mysqli_select_db($link,'sensor_readings_db') or die('Cannot select the DB');

    // esegui la query
    $result = $link->query($query_string); 

    $readings = array();	

    // cicla sul risultato
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

        $ID = $row['ID'];
        $temp = $row['temp'];
        $hum = $row['hum'];
        $time = $row['time'];

        $reading = array('ID' => $ID,'temp' =>$temp, 'hum' => $hum, 'time' => $time);
        array_push($readings, $reading);
    }

    $response = array('readings' => $readings, 'type' => 'load');
    echo json_encode($response);	
}
 
function uploadData(){
 
    if($_GET['temperature'] != '' and  $_GET['humidity'] != '' and  $_GET['ID'] != ''){
        $ID = $_GET['ID'];
        $temperature = $_GET['temperature'];
        $humidity = $_GET['humidity'];
        $link='';
        
        $link = mysqli_connect('localhost','PPM','localhost') or die('Cannot connect to the DB');
        mysqli_select_db($link,'sensor_readings_db') or die('Cannot select the DB');
        
        $mydate=getdate();
        $t = "$mydate[year]-$mydate[mday]-$mydate[mon] $mydate[hours]:$mydate[minutes]:$mydate[seconds]";
        $query = "insert into sensor_readings set ID = '".$ID."', hum='".$humidity."', temp='".$temperature."', time = '".$t."'";
        $result = mysqli_query(link,$query) or die('Errant query:  '.$query);
    }
}
function setCommands (){
    $PIN = $_GET['PIN'];
    $state = $_GET['state'];
    $query = "update commands set state = '".$state."' where OUTPIN = '".$OUTPIN ."'";
    $link = mysqli_connect('localhost','PPM','localhost') or die('Cannot connect to the DB');
    mysqli_select_db($link,'sensor_readings_db') or die('Cannot select the DB');
    $link->query($query_string); 
}
function getCommands (){
    $link = mysqli_connect('localhost','PPM','localhost') or die('Cannot connect to the DB');
    mysqli_select_db($link,'sensor_readings_db') or die('Cannot select the DB');
    $query_string = 'SELECT * FROM commands'; 
    echo $link->query($query_string); 
}
function getReading(){
    $PIN = $_GET['PIN'];
    
    $link = mysqli_connect('localhost','PPM','localhost') or die('Cannot connect to the DB');
    mysqli_select_db($link,'sensor_readings_db') or die('Cannot select the DB');
    
    $query_string = "SELECT temp FROM sensor_readings WHERE ID ='".$PIN."' and time=(SELECT max(time) FROM sensor_readings)"; 
    $result = $link->query($query_string);

    $row = $result->fetch_array(MYSQLI_ASSOC);

    echo json_encode($row); 
}
?>
