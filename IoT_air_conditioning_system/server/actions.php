<?php
    
    $action = $_GET['action'];

    switch($action) {

        case "upload" : 
            uploadData();
        break;
        case "download" :
            downloadData();
        break; 
        case "setCommand" :
            setCommand();
        break; 
        case "getCommand" :
            getCommand();
        break; 
        case "getReading" :
            getReading();
        break; 
        case "addRoom" :
            addRoom();
        break; 
        case "removeRoom" :
            removeRoom();
        break; 
        case "getReading" :
            getReading();
        break; 
        case "getBuilding" :
            getBuilding();
        break; 
        case "setPIN" :
            setPIN();
        break; 
	}

function downloadData() {
    //$mydate=getdate();
    //$day = $mydate[mday]-1;
    //$t = "$mydate[year]-$day-$mydate[mon] $mydate[hours]:$mydate[minutes]:$mydate[seconds]";
    $query_string = 'SELECT * FROM sensor_readings ORDER BY time DESC'; 
    //WHERE time>"'.$t.'"
    
    $link = mysqli_connect('localhost','PPM','localhost') or die('Cannot connect to the DB');
    mysqli_select_db($link,'air_conditioning_system_db') or die('Cannot select the DB');

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
    $ID = $_GET['ID'];
    $temperature = $_GET['temperature'];
    $humidity = $_GET['humidity'];
    $link='';

    $link = mysqli_connect('localhost','PPM','localhost') or die('Cannot connect to the DB');
    mysqli_select_db($link,'air_conditioning_system_db') or die('Cannot select the DB');

    $mydate=getdate();
    $t = "$mydate[year]-$mydate[mday]-$mydate[mon] $mydate[hours]:$mydate[minutes]:$mydate[seconds]";
    $query = "insert into sensor_readings set ID = '".$ID."', hum='".$humidity."', temp='".$temperature."', time = '".$t."'";
    $result = mysqli_query($link,$query) or die('Errant query:  '.$query);
    
}
function setCommand (){
    $PIN = $_GET['PIN'];
    $state = $_GET['state'];
    $query = "update commands set state = '".$state."' where PIN = '".$PIN ."'";
    $link = mysqli_connect('localhost','PPM','localhost') or die('Cannot connect to the DB');
    mysqli_select_db($link,'air_conditioning_system_db') or die('Cannot select the DB');
    $result = mysqli_query($link,$query) or die('Errant query:  '.$query);
}
function getCommand(){
    $PIN = $_GET['PIN'];
    $link = mysqli_connect('localhost','PPM','localhost') or die('Cannot connect to the DB');
    mysqli_select_db($link,'air_conditioning_system_db') or die('Cannot select the DB');
    $query_string = "SELECT state FROM commands WHERE PIN = '".$PIN ."'"; 
    $result = $link->query($query_string);

    $row = $result->fetch_array(MYSQLI_ASSOC);

    echo json_encode($row); 
}
function getReading(){
    $PIN = $_GET['PIN'];
    
    $link = mysqli_connect('localhost','PPM','localhost') or die('Cannot connect to the DB');
    mysqli_select_db($link,'air_conditioning_system_db') or die('Cannot select the DB');
    
    $query_string = "SELECT temp FROM sensor_readings WHERE ID ='".$PIN."' and time=(SELECT max(time) FROM sensor_readings)"; 
    $result = $link->query($query_string);

    $row = $result->fetch_array(MYSQLI_ASSOC);

    echo json_encode($row); 
}
function addRoom(){
    $name = $_GET['name'];
    $numSens = $_GET['numSens'];
    $numCond = $_GET['numCond'];
    $link = mysqli_connect('localhost','PPM','localhost') or die('Cannot connect to the DB');
    mysqli_select_db($link,'air_conditioning_system_db') or die('Cannot select the DB');
    $query = "insert into building set name = '".$name."', numSens='".$numSens."', numCond='".$numCond."'";
    $result = mysqli_query($link,$query) or die('Errant query:  '.$query);
}
function removeRoom(){
    $room = $_GET['room'];
    $link = mysqli_connect('localhost','PPM','localhost') or die('Cannot connect to the DB');
    mysqli_select_db($link,'air_conditioning_system_db') or die('Cannot select the DB');
    $query = "DELETE FROM `building` WHERE name = '".$room."'";
    $result = mysqli_query($link,$query) or die('Errant query:  '.$query);
    $query = "update PINs set room = 'NULL' where PIN = '".$PIN."'";
    $result = mysqli_query($link,$query) or die('Errant query:  '.$query);
}
function setPIN(){
    $room = $_GET['room'];
    $PIN = $_GET['PIN'];
    $link = mysqli_connect('localhost','PPM','localhost') or die('Cannot connect to the DB');
    mysqli_select_db($link,'air_conditioning_system_db') or die('Cannot select the DB');
    $query = "update PINs set room = '".$room."' where PIN = '".$PIN."'";
    $result = mysqli_query($link,$query) or die('Errant query:  '.$query);
}
function getBuilding(){
    $query_string = 'SELECT * FROM building'; 
    
    $link = mysqli_connect('localhost','PPM','localhost') or die('Cannot connect to the DB');
    mysqli_select_db($link,'air_conditioning_system_db') or die('Cannot select the DB');

    $result = $link->query($query_string); 

    $builing = array();	

    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

        $name = $row['name'];
        $numSens = $row['numSens'];
        $numCond = $row['numCond'];

        $room = array('name' => $name,'numSens' =>$numSens, 'numCond' => $numCond);
        array_push($builing, $room);
    }

    $response = array('$builing' => $builing, 'type' => 'load');
    echo json_encode($response);	 
}
?>
