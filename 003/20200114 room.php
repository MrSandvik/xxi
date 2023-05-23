<?php

	$bg1 = "/bg1.png";	// Logo image path
	$refresh = 60;		// Page reload interval in seconds
	
	$sqlConnection = new PDO("sqlsrv:Server=192.168.141.233;Database=protel", "proteluser", "protel915930");


	if (empty($_GET["room"])) {
		$roomNo = 5; }
	else {
		$roomNo = $_GET["room"]; }
		
	if (empty($_GET["date"]) || !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $_GET["date"])) {
		$date = date("Y-m-d"); }
	else {
		$date = $_GET["date"]; }
		
	if (empty($_GET["time"]) || !preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $_GET["time"])) {
		$time = date("H:i"); }
	else {
		$time = $_GET["time"]; }
	
	
	$sqlQuery_Room = "select zimmernr from room where refnr = $roomNo";
	$sqlStatement = $sqlConnection->query($sqlQuery_Room);
	$sqlRecords = $sqlStatement->fetch(PDO::FETCH_ASSOC);
		$room = $sqlRecords['zimmernr'];
	
	$sqlQuery_Details = "
		select top 1\n
			  room.refnr\n
			, room.subref\n
			, room.zimmernr\n
			, kunden.name1\n
			, convert(varchar, bktbuch.datum, 23) datum\n
			, bktbuch.anzeit\n
			, bktbuch.abzeit\n
		from bktbuch\n
		left join room on bktbuch.zimmernr = room.subref or bktbuch.zimmernr = room.refnr\n
		left join kunden on bktbuch.kundennr = kunden.kdnr\n
		where bktbuch.datum = '$date'\n
		  and bktbuch.anzeit <= '$time'\n
		  and (bktbuch.abzeit > '$time' or bktbuch.abzeit < bktbuch.anzeit)\n
		  and (room.subref = $roomNo or room.refnr = $roomNo)\n
		  and bktbuch.status = 0\n
		order by room.subref
	";  
	
	$sqlStatement = $sqlConnection->query($sqlQuery_Details);
	$sqlRecords = $sqlStatement->fetch(PDO::FETCH_ASSOC);
		if (empty($sqlRecords)) {
			$cust = "";
			$timespan = "";
			$bgOpacity = 1;
		} else {
			$cust = $sqlRecords['name1'];
			$timespan = $sqlRecords['anzeit']." - ".$sqlRecords['abzeit'];
			$bgOpacity = 0.2;
		}
		
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Konferanse</title>
  
  <meta http-equiv="refresh" content="<?php echo $refresh; ?>">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Mukta&display=swap" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  
  <style>
    .bg-logo {
	  height: 50vh;
	  bottom: 12vh;
	  position: fixed;
	  left: 50%;
	  transform: translateX(-50%);
	  z-index: -1;
	  opacity: <?php echo $bgOpacity; ?>;
    }
	
  </style>
  
  <script>
    function startTime() {
	  var today = new Date();
	  var h = today.getHours();
	  var m = today.getMinutes();
	  var D = today.getDate();
	  var M = today.getMonth() + 1;
	  var Y = today.getFullYear();
	  h = padSingle(h);
	  m = padSingle(m);
	  D = padSingle(D);
	  M = padSingle(M);
	  document.getElementById('time').innerHTML = h + ":" + m;
	  document.getElementById('date').innerHTML = D + "." + M + "." + Y;
	  var t = setTimeout(startTime, 500);
	}
	function padSingle(i) {
	  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
	  return i;
	}
  </script>
</head>

<body onload="startTime()">

  <img src="<?php echo $bg1; ?>" class="bg-logo">

  <div class="jumbotron jumbotron-fluid text-center">
    <h1 class="display-1 font-weight-bold" style="font-family: 'Mukta', sans-serif;">
	  <?php echo $room; ?>
	</h1>
  </div>
  <div class="container-fluid bg-transparent">
    <div class="row">
      <div class="col text-center">
	    <h1 class="display-1">
		  <?php echo $cust; ?>
		</h1>
	  </div>
    </div>

    <div class="row" style="position:absolute; bottom: 0px; width: 100%;">
      <div class="col text-center">
	    <h1 class="display-3">
		  <?php echo $timespan; ?>
		</h1>
	  </div>
      <div class="col-sm-1">
	  </div>
      <div class="col text-center">
	    <h1 class="display-4" id="time"></h1>
	    <h1 class="display-5" id="date"></h1>
	  </div>
    </div>
  </div>

  </body>
</html>

