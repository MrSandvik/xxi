<?php
// Copyright Godtnok.com 2020 -- All rights reserved
// Bjørn H. Sandvik <sandvik@godtnok.com>  (+47 403 11 664)

	$bg1 = "/bg1.jpg";	// Logo image path
	$opqHi = 1.0;		// Logo opacity when room vacant (0.0 - 1.0)
	$opqLo = 0.2;		// Logo opacity when room in use (0.0 - 1.0)
	
	$refresh = 60;		// Page reload interval in seconds
	
	$timeBefore = '01:00';	// HH:MM Time before start to display event
	$timeAfter =  '00:30';	// HH:MM Time after end to display event
	

	/*
4	Galleriet 1og2		22	Restaurant 5		37	Bowl´n Dine 6		53	Støtvig Kino
5	Styrerommet			23	Restaurant 6		38	Bowl´n Dine 7		54	Kokketeateret
6	Ida					24	Restaurant 7		39	Bowl´n Dine 8		55	Vinkjeller´n
7	Handlegata			25	Restaurant 8		40	Bowl´n Dine 9		56	Biblioteket
8	Vebjørn 			26	Restaurant 9		41	Bowl´n Dine 10		58	Trimrom
10	Restaurant 801		27	Restaurant 10		42	Vinterhage 1		60	Roligheten
11	Bowl´n Dine			28	Bowling 1			44	Vinterhage 2		61	105
12	Dahls Galleri 1		29	Bowling 2			45	Vinterhage 3		62	217
13	Dahls Galleri 2		30	Bowling 3			46	Vinterhage 4		63	SPA
16	Haakons Møtested	31	Bowling 4			47	Vinterhage 5		64	242
17	Vebjørns Møtested	32	Bowl´n Dine 1		48	Vinterhage 6		65	233
18	Restaurant 1		33	Bowl´n Dine 2		49	Vinterhage 7		66	202
19	Restaurant 2		34	Bowl´n Dine 3		50	Vinterhage 8			
20	Restaurant 3		35	Bowl´n Dine 4		51	Vinterhage 9			
21	Restaurant 4		36	Bowl´n Dine 5		52	Vinterhage 10			
 */

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
	
	$sqlConnection = new PDO("sqlsrv:Server=192.168.141.233;Database=protel", "proteluser", "protel915930");

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
		  and convert(datetime, bktbuch.anzeit) - '$timeBefore' <= '$time'\n
		  and (convert(datetime, bktbuch.abzeit) + '$timeAfter' > '$time' \n
		   or convert(datetime, bktbuch.abzeit) + '$timeAfter' < bktbuch.anzeit)\n
		  and (room.subref = $roomNo or room.refnr = $roomNo)\n
		  and bktbuch.status = 0\n
		order by room.subref
	";  
	
	$sqlStatement = $sqlConnection->query($sqlQuery_Details);
	$sqlRecords = $sqlStatement->fetch(PDO::FETCH_ASSOC);
		if (empty($sqlRecords)) {
			$cust = "";
			$timespan = "";
			$bgOpacity = $opqHi;
		} else {
			$cust = $sqlRecords['name1'];
			$timespan = $sqlRecords['anzeit']." - ".$sqlRecords['abzeit'];
			$bgOpacity = $opqLo;
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

