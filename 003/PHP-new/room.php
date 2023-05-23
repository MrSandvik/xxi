<?php
// Copyright Godtnok.com 2020 -- All rights reserved
// Bjørn H. Sandvik <sandvik@godtnok.com>  (+47 403 11 664)

$bg1 = "bg1.png";  // Logo image path
$opqHi = 0.80;   // Logo opacity when room vacant (0.0 - 1.0)
$opqLo = 0.08;   // Logo opacity when room in use (0.0 - 1.0)

$refresh = 60;   // Page reload interval in seconds

$timeBefore = '01:00'; // HH:MM Time before start to display event
$timeAfter = '00:30'; // HH:MM Time after end to display event

// MAKE NO CHANGES BELOW THIS LINE! //

date_default_timezone_set('Europe/Amsterdam');

if (empty($_GET["room"])) {
    $roomNo = 1;
} else {
    $roomNo = $_GET["room"];
}
if (empty($_GET["date"]) || !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $_GET["date"])) {
    $date = date("Y-m-d");
} else {
    $date = $_GET["date"];
}

if (empty($_GET["time"]) || !preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $_GET["time"])) {
    $time = date("H:i");
} else {
    $time = $_GET["time"];
}

//$sqlConnection = new PDO("odbc:protel", "proteluser", "protel915930");
try {
    $sqlConnection = new PDO("odbc:protel", "proteluser", "protel915930");
} catch (\Exception $e) {
    $XXI_errorLocation = basename(__FILE__, '.php') . " (Line: " . __LINE__ . "): ";
    require '../../dev.godtnok.xxi.master.errorHandler.php';
}
$tsql = "select zimmernr from protel.proteluser.room where refnr = $roomNo";
try {
    $sqlStatement_Details = $sqlConnection->query($tsql);

    if ($sqlConnection->errorInfo()[0] <> '00000') {
        echo "<pre>/*";
        print_r($sqlConnection->errorInfo());
        echo "<hr>*/";
        echo $tsql;
        die;
    }

    $sqlRecords = $sqlStatement_Details->fetchAll(PDO::FETCH_ASSOC);
    
    if (isset($_REQUEST["GetArray"])) {
        echo "<pre>";
        echo(json_encode($sqlRecords, JSON_PRETTY_PRINT));
        echo "</pre>";
        die;
    }
} catch (Exception $e) {
    $XXI_errorLocation = basename(__FILE__, '.php') . " (Line: " . __LINE__ . "): ";
    require 'dev.godtnok.xxi.master.errorHandler.php';
}

$room = $sqlRecords[0]['zimmernr'];
$room = utf8_encode($room);

$sqlQuery_Details = "
		select top 1\n
			  room.refnr\n
			, room.subref\n
			, room.zimmernr\n
			, isnull((select iif(len(data) <= 1, null, data) from protel.proteluser.metadata where xkey like 'display_customer_global' and type = 3000 and ref = bktbuch.leistacc) , kunden.name1) name1 \n
			, convert(varchar, bktbuch.datum, 23) datum\n
			, bktbuch.anzeit\n
			, bktbuch.abzeit\n
		from protel.proteluser.bktbuch\n
		left join protel.proteluser.room on bktbuch.zimmernr = room.subref or bktbuch.zimmernr = room.refnr\n
		left join protel.proteluser.kunden on bktbuch.kundennr = kunden.kdnr\n
		left join protel.proteluser.metadata on bktbuch.leistacc = metadata.ref and metadata.type = 3000 \n
													  and metadata.xkey = 'display_show_global'\n
		where bktbuch.datum = '$date'\n
		  and convert(datetime, bktbuch.anzeit) - '$timeBefore' <= '$time'\n
		  and (convert(datetime, bktbuch.abzeit) + '$timeAfter' > '$time' \n
		   or convert(datetime, bktbuch.abzeit) + '$timeAfter' < bktbuch.anzeit)\n
		  and (room.subref = $roomNo or room.refnr = $roomNo)\n
--		  and bktbuch.status = 0\n
		  and (metadata.data is null or metadata.data in(0, 2))\n
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
    $timespan = $sqlRecords['anzeit'] . " - " . $sqlRecords['abzeit'];
    $bgOpacity = $opqLo;
}
$cust = utf8_encode($cust);
?>


<!DOCTYPE html>

<html lang="en">
    <head>
        <title><?php echo $room; ?></title>

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
                src: url('lt-flode-neue-heavy.woff2') format("woff2"), 	/* chrome、firefox */
                    url('lt-flode-neue-heavy.woff') format("woff"), 		/* chrome、firefox */
                    url('lt-flode-neue-heavy.ttf') format("truetype"); 	/* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
            }
            @font-face {
                font-family: Detail;
                src: url("lt-flode-neue-heavy.woff") format("woff"), /* chrome、firefox */
                    url("lt-flode-neue-heavy.woff2") format("woff2"), /* chrome、firefox */
                    url("lt-flode-neue-heavy.ttf") format("truetype"); /* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
            }

            .branding {
                font-family:"Brand" !important;
                font-size:550%;
                font-style:normal;
                -webkit-font-smoothing: antialiased;
                -webkit-text-stroke-width: 0.1px;
                -moz-osx-font-smoothing: grayscale;
            }

            .bg-logo {
                width: 90vh;
                bottom: 40vh;
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
                if (i < 10) {
                    i = "0" + i
                }
                ;  // add zero in front of numbers < 10
                return i;
            }
        </script>
    </head>

    <body onload="startTime()" style="overflow: hidden;">

        <img src="<?php echo $bg1; ?>" class="bg-logo">

        <div class="jumbotron jumbotron-fluid text-center">
            <h1 class="display-1 font-weight-bold branding">
                        <?php echo $room; ?>
            </h1>
        </div>
        <div class="container-fluid bg-transparent">
            <div class="row">
                <div class="col text-center">
                    <h1 class="display-1" style="font-family:Detail !important;">
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

