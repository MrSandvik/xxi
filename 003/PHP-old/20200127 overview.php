<?php
// Copyright Godtnok.com 2020 -- All rights reserved
// Bjørn H. Sandvik <sandvik@godtnok.com>  (+47 403 11 664)

	$bg1 = "/bg1.jpg";		// Logo image path
	$opqHi = 0.2;			// Logo opacity when room vacant (0.0 - 1.0)
	$opqLo = 0.2;			// Logo opacity when room in use (0.0 - 1.0)
	
	$refresh = 60;			// Page reload interval in seconds
	$static = false;		// Static overview for the full day (true) or dynamic overview (false)
	
	$timeBefore = '01:00';	// HH:MM Time before start to display event
	$timeAfter =  '00:30';	// HH:MM Time after end to display event
	



// MAKE NO CHANGES BELOW THIS LINE! //


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

	if (!empty($_GET["static"]) && $_GET["static"] == 1) {
		$static = true; }
	elseif (!empty($_GET["static"]) && $_GET["static"] == 0) {
		$static = false; }
	
	$sqlConnection = new PDO("sqlsrv:Server=192.168.141.233;Database=protel", "proteluser", "protel915930");

	if(!$static) {
	$sqlQuery_Dynamic = "
		  and convert(datetime, bktbuch.anzeit) - '$timeBefore' <= '$time'\n
		  and (convert(datetime, bktbuch.abzeit) + '$timeAfter' > '$time' \n
		   or convert(datetime, bktbuch.abzeit) + '$timeAfter' < bktbuch.anzeit)\n
	"; }
	else {
	$sqlQuery_Dynamic = ""; }

	$sqlQuery_Details = "
		select\n
		\n
			  room.zimmernr\n
			, isnull((select iif(len(data) <= 1, null, data) from metadata where xkey like 'display_customer_global' and type = 3000 and ref = bktbuch.leistacc) , kunden.name1) name1 \n
			, convert(varchar, bktbuch.datum, 23) datum\n
			, bktbuch.anzeit\n
			, bktbuch.abzeit\n
		\n
		from bktbuch\n
		left join room on bktbuch.zimmernr = room.refnr\n
		left join kunden on bktbuch.kundennr = kunden.kdnr\n
		left join metadata on bktbuch.leistacc = metadata.ref and metadata.type = 3000 \n
															 and metadata.xkey = 'display_show_global'\n
		where bktbuch.datum = '$date'\n
		  and bktbuch.status = 0\n
		  and (metadata.data is null or metadata.data in(0, 2))\n
		  $sqlQuery_Dynamic\n
		\n
		order by anzeit, name1";  
;	

	$sqlStatement = $sqlConnection->query($sqlQuery_Details);
/*	$sqlTest = $sqlStatement->fetch(PDO::FETCH_ASSOC);

		if (empty($sqlTest)) {
			$bgOpacity = $opqHi;
		} else {
			$bgOpacity = $opqLo;
		}
echo $sqlQuery_Details;		*/
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>St&oslash;tvig Hotel</title>
  
  <meta http-equiv="refresh" content="<?php echo $refresh; ?>">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Mukta&display=swap" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  
  <style>
	@font-face {
	  font-family: Brand;
	  src: url('/PAG.woff2') format("woff2"), 	/* chrome、firefox */
	       url('/PAG.woff') format("woff"), 	/* chrome、firefox */
	       url('/PAG.ttf') format("truetype"); 	/* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
	}
	
	.branding {
		font-family:"Brand" !important;
		font-size:400%;
		font-style:normal;
		-webkit-font-smoothing: antialiased;
		-webkit-text-stroke-width: 0.2px;
		-moz-osx-font-smoothing: grayscale;
	}
	
	.bg-logo {
	  height: 70vh;
	  bottom: 12vh;
	  position: fixed;
	  left: 50%;
	  transform: translateX(-50%);
	  z-index: -1;
	  opacity: <?php echo $opqLo; ?>;
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
	  document.getElementById('time').innerHTML = "&nbsp;" + h + ":" + m;
	  document.getElementById('date').innerHTML = D + "." + M + "." + Y + "&nbsp;";
	  var t = setTimeout(startTime, 500);
	}
	function padSingle(i) {
	  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
	  return i;
	}
  </script>
</head>

<body onload="startTime()" style="overflow: hidden;">

  <img src="<?php echo $bg1; ?>" class="bg-logo">
  
  <div class="row" style="background-color: #EEE;">
    <div class="col-lg text-center branding">
	  Støtvig Hotel
    </div>
   </div>
  <p></p>
  <div class="container-fluid bg-transparent">

	  <table class="table border border-left-0 border-right-0">
		<!--thead>
		  <tr>
			<th>Firstname</th>
			<th>Lastname</th>
			<th>Email</th>
		  </tr>
		</thead-->
		
		<tbody>
	
<?php
	while ($sqlRecords = $sqlStatement->fetch()) {
        echo "<tr>";
        echo "  <td class='align-middle'><h2 class='font-weight-light'>".$sqlRecords['anzeit']." - ".$sqlRecords['abzeit']."</h1></td>";
        echo "  <td class='align-bottom'><h1 class='font-weight-light'>".substr($sqlRecords['name1'], 0, 29)."</h1></td>";
        echo "  <td class='align-middle'><h1 class='font-weight-light'>".$sqlRecords['zimmernr']."</h1></td>";
        echo "</tr>";
	}
?>
		</tbody>
	  </table>

  </div>

  <div class="container-fluid" style="background-color: #EEE; position: absolute; bottom: 0px;">
    <div class="row">
      <div class="col text-left">
	    <h3 class="font-weight-light" style="color: #888;" id="time"></h3>
	  </div>
	  <div class="col text-right">
	    <h3 class="font-weight-light" style="color: #888;" id="date"></h3>
	  </div>
    </div>
  </div>
  
</body>
</html>

