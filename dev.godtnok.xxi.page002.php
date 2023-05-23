<?php
/*
 * https://datatables.net/
 */


require 'dev.godtnok.xxi.master.variables.php';
require 'dev.godtnok.xxi.master.functions.php';
require 'dev.godtnok.xxi.page001.functions.php';

//Page rendering start:
require 'dev.godtnok.xxi.master.head.php';

//Error handling (suppressing warnings)
set_exception_handler('my_exception_handler');
error_reporting(E_ERROR);

function my_exception_handler($e) {
    error_log("SQL Error: (" . basename(__FILE__) . "): Data read error. $e");
}

// Read [Location] variable from URL and parse:
if (isset($_REQUEST["Location"])) {
  $XXI_mpeHotel = htmlspecialchars($_REQUEST["Location"]);

  if (!in_array($XXI_mpeHotel, $XXIv_Kitchen_mpehotel_enabled)) {
    $XXI_mpeHotel = $XXIv_Kitchen_mpehotel_default;
  }

  setcookie('XXI_locationCookie', $XXI_mpeHotel, $XXIv_Kitchen_locationCookie_expire);
} else {
  if (isset($_COOKIE['XXI_locationCookie'])) {
    $XXI_mpeHotel = $_COOKIE['XXI_locationCookie'];
    setcookie('XXI_locationCookie', $XXI_mpeHotel, $XXIv_Kitchen_locationCookie_expire);
  } else {
    $XXI_mpeHotel = $XXIv_Kitchen_mpehotel_default;
    setcookie('XXI_locationCookie', $XXI_mpeHotel, $XXIv_Kitchen_locationCookie_expire);
  }
}

// Read [Date] variable from URL and parse:
if (isset($_REQUEST["Date"])) {
  $XXI_ymdDate = htmlspecialchars($_REQUEST["Date"]);
} else {
  $XXI_ymdDate = "";
}
// If Date variable is valid, assign to SQL replace parameter.
//   Otherwise, use $XXI_Kitchen_date from dev.godtnok.xxi.master.variables.php
if ($XXI_ymdDate <> "") {
  if (XXIf_CheckYMD($XXI_ymdDate)) {
    $XXIv_Kitchen_date = $XXI_ymdDate;
  }
} else {
  $XXI_ymdDate = $XXIv_Kitchen_date;
}


$tsql = file_get_contents('002/dev.godtnok.xxi.page002.main.sql');
$tsql = str_replace("#DATABASE", $XXIv_DB_Database, $tsql);
$tsql = str_replace("#SCHEMA", $XXIv_DB_Schema, $tsql);
$tsql = str_replace("#DATE", $XXI_ymdDate, $tsql);
$tsql = str_replace("#MPEHOTEL", $XXI_mpeHotel, $tsql);
$tsql = str_replace("#BQHIDESTATUS", $XXIv_Kitchen_bqHideStatus, $tsql);
$tsql = str_replace("#BUHIDESTATUS", $XXIv_Kitchen_buHideResstatus, $tsql);
$tsql = str_replace("#ZIHIDEKAT", $XXIv_Kitchen_ziHideCategories, $tsql);

if (isset($_REQUEST["GetQuery"])) {
  // Load SqlFormatter library
  require_once 'resources/php/lib/SqlFormatter.php';
  // Break queries into array
  $xxi_queries = SqlFormatter::splitQuery($tsql);

  echo '    </head>' . "\n";
  echo '    <body>' . "\n";

  require '002/dev.godtnok.xxi.main.navbarTop.php';
  require '002/dev.godtnok.xxi.main.navbarBottom.php';

  echo '        <div class="container" style="height: calc(100% - 82px); top: 52px; position: relative;">' . "\n";

  foreach ($xxi_queries as $xxi_query) {
    echo '            <div class="bg-light border border-2 border-white rounded-1">' . "\n";
    echo '                ' . SqlFormatter::highlight($xxi_query);
    echo '            </div>' . "\n";
  }

  echo '        </div>' . "\n";
  echo '    </body>' . "\n";
  echo '</html>';
  die;
}



try {
  $sqlConnection = new PDO("odbc:$XXIv_DB_DSN", $XXIv_DB_User, $XXIv_DB_Password);
} catch (\Exception $e) {
  $XXI_errorLocation = basename(__FILE__, '.php') . " (Line: " . __LINE__ . "): ";
  require 'dev.godtnok.xxi.master.errorHandler.php';
}
try {
  $sqlStatement_Details = $sqlConnection->query($tsql);

  if ($sqlConnection->errorInfo()[0] <> '00000') {
    print_r($sqlConnection->errorInfo());
  }


  $XXI_sqlRecords = $sqlStatement_Details->fetchAll(PDO::FETCH_ASSOC);
  $XXI_sqlCount = count($XXI_sqlRecords);

  if (isset($_REQUEST["GetArray"])) {
    echo "<pre>";
    echo(json_encode($XXI_sqlRecords, JSON_PRETTY_PRINT));
    echo "</pre>";
    die;
  }
} catch (Exception $e) {
  $XXI_errorLocation = basename(__FILE__, '.php') . " (Line: " . __LINE__ . "): ";
  require 'dev.godtnok.xxi.master.errorHandler.php';
}
?>

<link rel="stylesheet" type="text/css" href="/resources/datatables/datatables.min.css?v=1.1">
<link rel="stylesheet" href="/resources/css/dev.godtnok.xxi.master.splitPanes.css?v=1.1">
<link rel="stylesheet" href="/resources/css/dev.godtnok.xxi.master.dividers.css?v=1.1">

<script src="/resources/datatables/datatables.min.js"></script>
<script src="/resources/js/dev.godtnok.xxi.master.splitPanes.js"></script>

<style type="text/css">

    html, body {
        height: 100%;
        min-height: 100%;
        margin: 0;
        padding: 0;
    }

    #top-component {
        bottom: 40%;
        margin-bottom: 5px;
        min-height: 5em;
    }

    #my-divider {
        bottom: 40%; 
        height: 5px;
    }

    #bottom-component {
        height: 40%;
        min-height: 10em;
    }

</style>

<script>
  $(function () {
      $('div.split-pane').splitPane();
      $('button:first').on('click', function () {
          $('div.split-pane').splitPane('lastComponentSize', 200);
      });
      $('button:last').on('click', function () {
          $('div.split-pane').splitPane('firstComponentSize', 0);
      });
  });
</script>


</head>
<body>

    <?php require '002/dev.godtnok.xxi.main.navbarTop.php'; ?>
    <?php require '002/dev.godtnok.xxi.main.navbarBottom.php'; ?>

    <div class="container-fluid" style="height: calc(100% - 82px); top: 52px; position: relative;">


        <div class="split-pane horizontal-percent"> <!-- REQUIRED -->
            <div class="split-pane-component" id="top-component"> <!-- REQUIRED -->

                <div class="container-fluid" id="topSection">

                    <?php require '002/filler.php'; ?>

                </div>
            
            </div>

            <div class="split-pane-divider bg-secondary" id="my-divider"></div> <!-- REQUIRED -->

            <div class="split-pane-component" id="bottom-component"> <!-- REQUIRED -->

                <div class="container-fluid" style="margin-top: 3px;">

                    <?php require '002/filler2.php'; ?>

                </div>
                
            </div>
            
        </div>

    </div>



    <script>
      $('#sortTable').DataTable({
          fixedHeader: true,
          paging: false,
          info: false,
          order: [],
          columnDefs: [
              {orderable: false, targets: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]},
              {searchable: false, targets: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]}
          ]
      });
    </script>



</body>
</html>
